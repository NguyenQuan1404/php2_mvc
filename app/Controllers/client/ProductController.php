<?php
namespace App\Controllers\Client;

use Controller;

class ProductController extends Controller
{
    // URL: /product (Trang danh sách)
    public function index()
    {
        $cachePath = __DIR__ . '/../../storage/cache'; 
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) @unlink($file); 
            }
        }

        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');
        $brandModel = $this->model('Brand');

        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'category_id' => $_GET['category'] ?? '',
            'brand_id' => $_GET['brand'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        if (method_exists($productModel, 'getFilteredProducts')) {
            $products = $productModel->getFilteredProducts($filters);
        } elseif (method_exists($productModel, 'getActiveProducts')) {
            $products = $productModel->getActiveProducts(); 
        } else {
            $products = $productModel->index();
        }
        
        $categories = method_exists($categoryModel, 'index') ? $categoryModel->index() : []; 
        $brands = method_exists($brandModel, 'index') ? $brandModel->index() : [];

        $this->view('clientviews.product.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $filters,
            'title' => 'Tất cả sản phẩm - Vua Bóng Đá'
        ]);
    }

    // URL: /product/detail/{id}
    public function detail($id)
    {
        $productModel = $this->model('Product');
        $variantModel = $this->model('ProductVariant');

        $product = $productModel->getDetail($id);

        if (!$product) {
            $this->notFound("Sản phẩm không tồn tại.");
            return;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['recently_viewed'])) {
            $_SESSION['recently_viewed'] = [];
        }

        if (($key = array_search($id, $_SESSION['recently_viewed'])) !== false) {
            unset($_SESSION['recently_viewed'][$key]);
        }
        
        array_unshift($_SESSION['recently_viewed'], $id);
        
        if (count($_SESSION['recently_viewed']) > 5) {
            array_pop($_SESSION['recently_viewed']);
        }

        $recentIds = array_filter($_SESSION['recently_viewed'], function($val) use ($id) {
            return $val != $id;
        });
        
        $recentProducts = [];
        if (!empty($recentIds)) {
            $recentProducts = $productModel->getListByIds($recentIds);
        }

        $variants = $variantModel->getByProductId($id);
        $relatedProducts = $productModel->getRelatedProducts($product['category_id'], $id);

        $this->view('clientviews.product.detail', [
            'product' => $product,
            'variants' => $variants,
            'relatedProducts' => $relatedProducts,
            'recentProducts' => $recentProducts,
            'title' => $product['name']
        ]);
    }

    // --- LOGIC SO SÁNH SẢN PHẨM (COMPARE) ---
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

    public function addCompare($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['compare'])) $_SESSION['compare'] = [];

        if (!in_array($id, $_SESSION['compare'])) {
            if (count($_SESSION['compare']) >= 3) {
                array_shift($_SESSION['compare']);
            }
            $_SESSION['compare'][] = $id;
            $_SESSION['success'] = "Đã thêm sản phẩm vào danh sách so sánh!";
        } else {
            $_SESSION['error'] = "Sản phẩm đã có trong danh sách so sánh!";
        }

        if (isset($_SERVER['HTTP_REFERER'])) header('Location: ' . $_SERVER['HTTP_REFERER']);
        else header('Location: /product');
        exit;
    }

    public function removeCompare($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['compare'])) {
            if (($key = array_search($id, $_SESSION['compare'])) !== false) {
                unset($_SESSION['compare'][$key]);
                $_SESSION['success'] = "Đã xóa khỏi danh sách so sánh!";
            }
        }

        header('Location: /product/compare');
        exit;
    }

    // --- LOGIC YÊU THÍCH SẢN PHẨM (WISHLIST) ---
    public function wishlist()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $wishlistIds = $_SESSION['wishlist'] ?? [];
        $products = [];

        if (!empty($wishlistIds)) {
            $productModel = $this->model('Product');
            $products = $productModel->getListByIds($wishlistIds);
        }

        $this->view('clientviews.product.wishlist', [
            'products' => $products,
            'title' => 'Sản phẩm yêu thích'
        ]);
    }

    public function addWishlist($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = [];

        if (!in_array($id, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $id;
            $_SESSION['success'] = "Đã thêm vào danh sách yêu thích <i class='fas fa-heart text-danger'></i>";
        } else {
            $_SESSION['error'] = "Sản phẩm này đã có trong danh sách yêu thích của bạn rồi!";
        }

        if (isset($_SERVER['HTTP_REFERER'])) header('Location: ' . $_SERVER['HTTP_REFERER']);
        else header('Location: /product');
        exit;
    }

    public function removeWishlist($id)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION['wishlist'])) {
            if (($key = array_search($id, $_SESSION['wishlist'])) !== false) {
                unset($_SESSION['wishlist'][$key]);
                $_SESSION['success'] = "Đã bỏ yêu thích sản phẩm!";
            }
        }

        if (isset($_SERVER['HTTP_REFERER'])) header('Location: ' . $_SERVER['HTTP_REFERER']);
        else header('Location: /product/wishlist');
        exit;
    }
}