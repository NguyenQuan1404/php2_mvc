<?php
namespace App\Controllers\Client;

use Controller;

class CheckoutController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        if (empty($_SESSION['cart'])) {
            header('Location: /cart/index');
            exit;
        }

        $user = $_SESSION['user_client'] ?? $_SESSION['user'] ?? null; 

        if (!$user) {
            $_SESSION['redirect_after_login'] = '/checkout/index';
            $_SESSION['error'] = 'Vui lòng đăng nhập để thanh toán.';
            header('Location: /auth/login');
            exit;
        }

        $cart = $_SESSION['cart'];
        $totalPrice = 0;
        
        // --- PRO: CHECK LẠI KHO LẦN CUỐI KHI VÀO TRANG CHECKOUT ---
        // (Phòng trường hợp khách để giỏ hàng 2 ngày mới vào thanh toán)
        $hasError = false;
        foreach ($cart as $key => $item) {
            $stock = 0;
            if ($item['variant_id']) {
                $v = $this->model('ProductVariant')->find($item['variant_id']);
                $stock = $v['quantity'] ?? 0;
            } else {
                $p = $this->model('Product')->find($item['id']);
                $stock = $p['quantity'] ?? 0;
            }

            if ($item['quantity'] > $stock) {
                // Tự động cập nhật lại số lượng hoặc báo lỗi
                $_SESSION['error'] = "Sản phẩm " . $item['name'] . " hiện chỉ còn " . $stock . ". Vui lòng cập nhật giỏ hàng.";
                header('Location: /cart/index');
                exit;
            }
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $this->view('clientviews.checkout.index', [
            'cart' => $cart, 
            'totalPrice' => $totalPrice,
            'user' => $user
        ]);
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_SESSION['cart'])) {
                header('Location: /');
                exit;
            }

            $user = $_SESSION['user_client'] ?? $_SESSION['user'] ?? null;
            if (!$user) {
                header('Location: /auth/login');
                exit;
            }

            // --- PRO: CHỐT LẠI KHO MỘT LẦN NỮA NGAY TRƯỚC KHI TẠO ĐƠN ---
            // Đây là bước quan trọng nhất để tránh 2 người cùng mua 1 sản phẩm cuối
            foreach ($_SESSION['cart'] as $item) {
                 if ($item['variant_id']) {
                    $v = $this->model('ProductVariant')->find($item['variant_id']);
                    if ($item['quantity'] > $v['quantity']) {
                        echo "<script>alert('Rất tiếc, sản phẩm {$item['name']} vừa hết hàng.'); window.location.href='/cart/index';</script>";
                        exit;
                    }
                } else {
                    $p = $this->model('Product')->find($item['id']);
                    if ($item['quantity'] > $p['quantity']) {
                        echo "<script>alert('Rất tiếc, sản phẩm {$item['name']} vừa hết hàng.'); window.location.href='/cart/index';</script>";
                        exit;
                    }
                }
            }

            $totalMoney = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalMoney += $item['price'] * $item['quantity'];
            }

            $orderData = [
                'user_id' => $user['id'],
                'fullname' => $_POST['fullname'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'note' => $_POST['note'] ?? '',
                'total_money' => $totalMoney
            ];

            $orderModel = $this->model('Order');
            $orderId = $orderModel->create($orderData);

            if ($orderId) {
                $orderDetailModel = $this->model('OrderDetail');
                foreach ($_SESSION['cart'] as $item) {
                    $detailData = [
                        'order_id' => $orderId,
                        'product_id' => $item['id'],
                        'variant_id' => $item['variant_id'] ?? null, // QUAN TRỌNG: Lưu variant ID
                        'product_name' => $item['name'], 
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total' => $item['price'] * $item['quantity']
                    ];
                    $orderDetailModel->create($detailData);
                    
                    // LƯU Ý: Ở đây ta CHƯA trừ kho ngay. 
                    // Thường kho sẽ trừ khi Admin xác nhận đơn (Processing).
                    // Tuy nhiên, nếu muốn chắc ăn giữ hàng cho khách, bạn có thể trừ kho ngay tại đây.
                    // Với quy trình hiện tại của OrderController, ta sẽ trừ khi Admin xác nhận.
                }

                unset($_SESSION['cart']);
                $_SESSION['msg_success'] = "Đặt hàng thành công! Mã đơn hàng #$orderId.";
                header('Location: /myorder/index');
                exit;
            } else {
                echo "Lỗi hệ thống: Không thể tạo đơn hàng.";
            }
        }
    }
    // Thêm hàm này vào CheckoutController
public function applyCoupon() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $code = $input['code'] ?? '';
        $totalOrder = $input['total_order'] ?? 0;

        $couponModel = $this->model('Coupon');
        $coupon = $couponModel->findByCode($code);

        if (!$coupon) {
            echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn!']);
            exit;
        }

        if ($totalOrder < $coupon['min_order_value']) {
             echo json_encode(['status' => 'error', 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu: ' . number_format($coupon['min_order_value'])]);
             exit;
        }

        // Tính tiền giảm
        $discountAmount = 0;
        if ($coupon['type'] == 'fixed') {
            $discountAmount = $coupon['value'];
        } else {
            $discountAmount = ($totalOrder * $coupon['value']) / 100;
        }

        // Đảm bảo không giảm quá giá trị đơn hàng
        if ($discountAmount > $totalOrder) $discountAmount = $totalOrder;

        echo json_encode([
            'status' => 'success', 
            'discount' => $discountAmount, 
            'coupon_code' => $code,
            'message' => 'Áp dụng mã thành công!'
        ]);
        exit;
    }
}

}