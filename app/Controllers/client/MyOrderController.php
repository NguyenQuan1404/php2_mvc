<?php
namespace App\Controllers\Client;

use Controller;

class MyOrderController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user = $_SESSION['user_client'] ?? $_SESSION['user'] ?? null;
        if (!$user) {
            header('Location: /auth/login');
            exit;
        }
    }

    public function index() {
        $user = $_SESSION['user_client'] ?? $_SESSION['user'];
        $userId = $user['id'];
        
        $orderModel = $this->model('Order');
        $orders = $orderModel->getOrdersByUserId($userId);

        $msgSuccess = $_SESSION['msg_success'] ?? null;
        $msgError = $_SESSION['msg_error'] ?? null;
        
        unset($_SESSION['msg_success']);
        unset($_SESSION['msg_error']);

        $this->view('clientviews.order.index', [
            'orders' => $orders,
            'msg_success' => $msgSuccess,
            'msg_error' => $msgError
        ]);
    }

    public function detail() {
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            header('Location: /myorder/index');
            exit;
        }

        $user = $_SESSION['user_client'] ?? $_SESSION['user'];
        $userId = $user['id'];
        
        $orderModel = $this->model('Order');
        $order = $orderModel->find($orderId);

        if (!$order || $order['user_id'] != $userId) {
            echo "Bạn không có quyền xem đơn hàng này.";
            exit;
        }

        $orderDetailModel = $this->model('OrderDetail');
        $details = $orderDetailModel->getItemsByOrderId($orderId);

        $this->view('clientviews.order.detail', [
            'order' => $order,
            'details' => $details
        ]);
    }

    // --- HỦY ĐƠN HÀNG (Chỉ Pending) ---
    public function cancel() {
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            header('Location: /myorder/index');
            exit;
        }

        $user = $_SESSION['user_client'] ?? $_SESSION['user'];
        $userId = $user['id'];

        $orderModel = $this->model('Order');
        $order = $orderModel->find($orderId);

        if (!$order || $order['user_id'] != $userId) {
            $_SESSION['msg_error'] = "Đơn hàng không tồn tại.";
            header('Location: /myorder/index');
            exit;
        }

        if ($order['status'] === 'pending') {
            $orderModel->updateStatus($orderId, 'cancelled');
            $_SESSION['msg_success'] = "Đã hủy đơn hàng #$orderId thành công.";
        } else {
            $_SESSION['msg_error'] = "Không thể hủy đơn hàng đã được xác nhận.";
        }

        header('Location: /myorder/index');
        exit;
    }

    // --- MUA LẠI (RE-ORDER) ---
    public function reorder() {
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            header('Location: /myorder/index');
            exit;
        }

        // Lấy chi tiết đơn cũ
        $orderDetailModel = $this->model('OrderDetail');
        $oldItems = $orderDetailModel->getItemsByOrderId($orderId);

        if (empty($oldItems)) {
            header('Location: /myorder/index');
            exit;
        }

        $productModel = $this->model('Product');
        $variantModel = $this->model('ProductVariant');
        
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        $addedCount = 0;
        $errorCount = 0;

        foreach ($oldItems as $item) {
            $productId = $item['product_id'];
            $variantId = $item['variant_id'] ?? null; // Cột này cần có trong order_details (đã nhắc ở bước trước)
            $quantity = $item['quantity'];

            // Kiểm tra sản phẩm cha
            $product = $productModel->find($productId);
            if (!$product || $product['status'] == 0) {
                $errorCount++; // SP đã bị xóa hoặc ẩn
                continue; 
            }

            $availableStock = $product['quantity'];
            $price = $product['price'];
            $image = $product['image'];
            $name = $product['name'];
            $variantText = '';

            // Kiểm tra biến thể (nếu có)
            if ($variantId) {
                $variant = $variantModel->find($variantId);
                if (!$variant) {
                    $errorCount++; // Biến thể không còn
                    continue; 
                }
                $availableStock = $variant['quantity'];
                if ($variant['price'] > 0) $price = $variant['price'];
                if (!empty($variant['image'])) $image = $variant['image'];
                $variantText = isset($variant['name']) ? ' (' . $variant['name'] . ')' : ' (' . $variant['size'] . ' - ' . $variant['color'] . ')';
                $name .= $variantText;
            }

            // Check kho
            if ($availableStock < $quantity) {
                $errorCount++; // Không đủ hàng
                continue; 
            }

            // Tạo item giỏ hàng
            $cartItem = [
                'id' => $productId,
                'name' => $name,
                'price' => $price, // Lấy giá MỚI NHẤT
                'image' => $image,
                'quantity' => $quantity,
                'variant_id' => $variantId,
                'variant_text' => $variantText
            ];

            $cartKey = $productId . ($variantId ? '_' . $variantId : '');
            
            // Cộng dồn vào giỏ
            if (isset($_SESSION['cart'][$cartKey])) {
                if (($_SESSION['cart'][$cartKey]['quantity'] + $quantity) <= $availableStock) {
                    $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
                    $addedCount++;
                } else {
                    $errorCount++;
                }
            } else {
                $_SESSION['cart'][$cartKey] = $cartItem;
                $addedCount++;
            }
        }

        if ($addedCount > 0) {
            $_SESSION['msg_success'] = "Đã thêm $addedCount sản phẩm vào giỏ hàng.";
            if ($errorCount > 0) {
                $_SESSION['msg_error'] = "Có $errorCount sản phẩm không thể thêm (do hết hàng).";
            }
            header('Location: /cart/index');
        } else {
            $_SESSION['msg_error'] = "Không thể mua lại (Sản phẩm hết hàng hoặc ngừng kinh doanh).";
            header('Location: /myorder/index');
        }
        exit;
    }
}