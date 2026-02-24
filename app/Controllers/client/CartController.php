<?php
namespace App\Controllers\Client;

use Controller;
class CartController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    }

    public function index() {
        $cart = $_SESSION['cart'];
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        $this->view('clientviews.cart.index', ['cart' => $cart, 'totalPrice' => $totalPrice]);
    }

    // Thêm vào giỏ hàng (CÓ CHECK KHO)
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $variantId = isset($_POST['variant_id']) && !empty($_POST['variant_id']) ? $_POST['variant_id'] : null;
            
            $productModel = $this->model('Product');
            $variantModel = $this->model('ProductVariant');
            
            $product = $productModel->find($productId);

            if ($product) {
                // Kiểm tra tồn kho trước khi thêm
                $availableStock = $product['quantity']; // Mặc định là kho tổng
                $cartItem = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $quantity,
                    'variant_id' => null,
                    'variant_text' => ''
                ];

                if ($variantId) {
                    $variant = $variantModel->find($variantId);
                    if ($variant) {
                        $availableStock = $variant['quantity']; // Nếu có biến thể, dùng kho biến thể
                        
                        if ($variant['price'] > 0) $cartItem['price'] = $variant['price'];
                        if (!empty($variant['image'])) $cartItem['image'] = $variant['image'];
                        
                        $cartItem['variant_id'] = $variantId;
                        $cartItem['variant_text'] = isset($variant['name']) ? ' (' . $variant['name'] . ')' : ' (' . $variant['size'] . ' - ' . $variant['color'] . ')';
                        $cartItem['name'] .= $cartItem['variant_text'];
                    }
                }

                // TẠO KEY GIỎ HÀNG
                $cartKey = $productId . ($variantId ? '_' . $variantId : '');
                
                // Tính toán số lượng dự kiến (trong giỏ + thêm mới)
                $currentInCart = isset($_SESSION['cart'][$cartKey]) ? $_SESSION['cart'][$cartKey]['quantity'] : 0;
                $newQuantity = $currentInCart + $quantity;

                // CHECK KHO PRO
                if ($newQuantity > $availableStock) {
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        echo json_encode(['status' => 'error', 'message' => "Chỉ còn $availableStock sản phẩm trong kho!"]);
                        exit;
                    } else {
                        // Flash message nếu không dùng AJAX
                        echo "<script>alert('Không đủ hàng trong kho!'); window.history.back();</script>"; 
                        exit;
                    }
                }

                if (isset($_SESSION['cart'][$cartKey])) {
                    $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$cartKey] = $cartItem;
                }
            }

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['status' => 'success', 'message' => 'Đã thêm vào giỏ']);
                exit;
            }
            
            header('Location: /cart/index');
        }
    }

    // Update AJAX (CÓ CHECK KHO)
    public function updateAjax() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $cartKey = $input['product_id'] ?? null;
        $quantity = $input['quantity'] ?? 1;
        $action = $input['action'] ?? 'update';

        if (!$cartKey || !isset($_SESSION['cart'][$cartKey])) {
            echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }

        if ($action === 'remove' || $quantity <= 0) {
            unset($_SESSION['cart'][$cartKey]);
        } else {
            // Lấy lại thông tin tồn kho để check
            $item = $_SESSION['cart'][$cartKey];
            $availableStock = 0;
            
            if ($item['variant_id']) {
                 $variant = $this->model('ProductVariant')->find($item['variant_id']);
                 $availableStock = $variant ? $variant['quantity'] : 0;
            } else {
                 $product = $this->model('Product')->find($item['id']);
                 $availableStock = $product ? $product['quantity'] : 0;
            }

            if ($quantity > $availableStock) {
                echo json_encode(['status' => 'error', 'message' => "Kho chỉ còn $availableStock!"]);
                exit;
            }

            $_SESSION['cart'][$cartKey]['quantity'] = $quantity;
        }

        $totalMoney = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalMoney += $item['price'] * $item['quantity'];
        }

        echo json_encode([
            'status' => 'success', 
            'total_money' => $totalMoney,
            'cart_count' => count($_SESSION['cart']),
            'item_total' => isset($_SESSION['cart'][$cartKey]) ? $_SESSION['cart'][$cartKey]['price'] * $_SESSION['cart'][$cartKey]['quantity'] : 0
        ]);
        exit;
    }

    public function remove() {
        $cartKey = $_GET['id'] ?? null;
        if ($cartKey && isset($_SESSION['cart'][$cartKey])) {
            unset($_SESSION['cart'][$cartKey]);
        }
        header('Location: /cart/index');
    }
}