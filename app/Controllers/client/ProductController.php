<?php
namespace App\Controllers\Client;

use Controller;

class ProductController extends Controller
{
    // URL: /product (Trang danh sách)
    public function index()
    {
        // Fix cache tự động
        $cachePath = __DIR__ . '/../../storage/cache'; 
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) @unlink($file); 
            }
        }

        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');

        $products = $productModel->getActiveProducts();
        $categories = $categoryModel->index();

        $this->view('clientviews.product.index', [
            'products' => $products,
            'categories' => $categories,
            'title' => 'Tất cả sản phẩm - Vua Bóng Đá'
        ]);
    }

    // URL: /product/detail/{id}
    public function detail($id)
    {
        $productModel = $this->model('Product');
        $variantModel = $this->model('ProductVariant');

        // 1. Lấy thông tin sản phẩm
        $product = $productModel->getDetail($id);

        if (!$product) {
            $this->notFound("Sản phẩm không tồn tại.");
            return;
        }

        // --- LOGIC SẢN PHẨM ĐÃ XEM (RECENTLY VIEWED) ---
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['recently_viewed'])) {
            $_SESSION['recently_viewed'] = [];
        }

        // Xóa ID nếu đã tồn tại để đưa lên đầu danh sách
        if (($key = array_search($id, $_SESSION['recently_viewed'])) !== false) {
            unset($_SESSION['recently_viewed'][$key]);
        }
        
        // Thêm vào đầu mảng
        array_unshift($_SESSION['recently_viewed'], $id);
        
        // Chỉ giữ lại 5 sản phẩm gần nhất
        if (count($_SESSION['recently_viewed']) > 5) {
            array_pop($_SESSION['recently_viewed']);
        }

        // Lấy danh sách sản phẩm đã xem (trừ sản phẩm đang xem hiện tại)
        $recentIds = array_filter($_SESSION['recently_viewed'], function($val) use ($id) {
            return $val != $id;
        });
        
        $recentProducts = [];
        if (!empty($recentIds)) {
            $recentProducts = $productModel->getListByIds($recentIds);
        }
        // ------------------------------------------------

        // 2. Lấy biến thể & sản phẩm liên quan
        $variants = $variantModel->getByProductId($id);
        $relatedProducts = $productModel->getRelatedProducts($product['category_id'], $id);

        $this->view('clientviews.product.detail', [
            'product' => $product,
            'variants' => $variants,
            'relatedProducts' => $relatedProducts,
            'recentProducts' => $recentProducts, // Truyền biến này sang view
            'title' => $product['name']
        ]);
    }

    // --- LOGIC SO SÁNH SẢN PHẨM (COMPARE) ---

    // 1. Trang hiển thị bảng so sánh
    public function compare()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $compareIds = $_SESSION['compare'] ?? [];
        $products = [];

        if (!empty($compareIds)) {
            $productModel = $this->model('Product');
            $products = $productModel->getListByIds($compareIds);
        }

        $this->view('clientviews.product.compare', [
            'products' => $products,
            'title' => 'So sánh sản phẩm'
        ]);
    }

    // 2. Thêm sản phẩm vào so sánh
    public function addCompare($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['compare'])) {
            $_SESSION['compare'] = [];
        }

        // Kiểm tra xem đã có chưa
        if (!in_array($id, $_SESSION['compare'])) {
            // Giới hạn so sánh tối đa 3 sản phẩm
            if (count($_SESSION['compare']) >= 3) {
                // Xóa sản phẩm đầu tiên (cũ nhất) đi
                array_shift($_SESSION['compare']);
            }
            $_SESSION['compare'][] = $id;
        }

        // Quay lại trang trước đó
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: /products');
        }
        exit;
    }

    // 3. Xóa sản phẩm khỏi so sánh
    public function removeCompare($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['compare'])) {
            if (($key = array_search($id, $_SESSION['compare'])) !== false) {
                unset($_SESSION['compare'][$key]);
            }
        }

        header('Location: /product/compare');
        exit;
    }
}