<?php
namespace App\Controllers\Admin;

use Controller; 

class OrderController extends Controller {

    public function index() {
        $orderModel = $this->model('Order');
        $orders = $orderModel->getAllOrders();
        $this->view('adminviews.order.index', ['orders' => $orders]);
    }

    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/order/index');
            exit;
        }

        $orderModel = $this->model('Order');
        $order = $orderModel->find($id);

        $orderDetailModel = $this->model('OrderDetail');
        $details = $orderDetailModel->getItemsByOrderId($id);

        $this->view('adminviews.order.detail', [
            'order' => $order,
            'details' => $details
        ]);
    }

    // --- LOGIC CẬP NHẬT TRẠNG THÁI & KHO (BẢN PRO) ---
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['id'];
            $newStatus = $_POST['status'];
            
            $orderModel = $this->model('Order');
            $orderDetailModel = $this->model('OrderDetail');
            $productModel = $this->model('Product');
            $variantModel = $this->model('ProductVariant');

            $currentOrder = $orderModel->find($orderId);
            $oldStatus = $currentOrder['status'];

            // Định nghĩa nhóm trạng thái
            $deductedStatuses = ['processing', 'shipping', 'completed']; // Đã trừ kho
            $availableStatuses = ['pending', 'cancelled']; // Chưa trừ kho / Đã hoàn kho

            $isOldDeducted = in_array($oldStatus, $deductedStatuses);
            $isNewDeducted = in_array($newStatus, $deductedStatuses);

            // LOGIC XỬ LÝ KHO
            
            // 1. CHUYỂN TỪ "CHƯA TRỪ" -> "TRỪ KHO" (Pending -> Processing)
            if (!$isOldDeducted && $isNewDeducted) {
                $items = $orderDetailModel->getItemsByOrderId($orderId);
                
                // Kiểm tra đủ hàng trước khi trừ toàn bộ
                foreach ($items as $item) {
                    $hasStock = true;
                    if (!empty($item['variant_id'])) {
                        $v = $variantModel->find($item['variant_id']);
                        if (!$v || $v['quantity'] < $item['quantity']) $hasStock = false;
                    } else {
                        $p = $productModel->find($item['product_id']);
                        if (!$p || $p['quantity'] < $item['quantity']) $hasStock = false;
                    }

                    if (!$hasStock) {
                        // Nếu 1 sản phẩm thiếu hàng -> Không cho xác nhận đơn
                        // Bạn có thể dùng Session Flash Message để báo lỗi đẹp hơn
                        echo "<script>alert('LỖI: Sản phẩm {$item['product_name']} không đủ tồn kho để xác nhận đơn này!'); window.history.back();</script>";
                        exit;
                    }
                }

                // Nếu đủ hàng thì tiến hành trừ
                foreach ($items as $item) {
                    if (!empty($item['variant_id'])) {
                        $variantModel->decreaseStock($item['variant_id'], $item['quantity']);
                    } else {
                        $productModel->decreaseStock($item['product_id'], $item['quantity']);
                    }
                }
            }
            
            // 2. CHUYỂN TỪ "ĐÃ TRỪ" -> "HOÀN KHO" (Đang giao/HT -> Hủy)
            elseif ($isOldDeducted && !$isNewDeducted) {
                $items = $orderDetailModel->getItemsByOrderId($orderId);
                foreach ($items as $item) {
                    if (!empty($item['variant_id'])) {
                        $variantModel->increaseStock($item['variant_id'], $item['quantity']);
                    } else {
                        $productModel->increaseStock($item['product_id'], $item['quantity']);
                    }
                }
            }

            // Cập nhật trạng thái đơn hàng
            $orderModel->updateStatus($orderId, $newStatus);
            
            header("Location: /admin/order/detail?id=$orderId");
        }
    }
}