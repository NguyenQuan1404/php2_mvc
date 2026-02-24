<?php
namespace App\Controllers\Client;

use Controller;

class HomeController extends Controller {
    public function index() {
        
        // 1. Lấy sản phẩm mới
        $productModel = $this->model('Product');
        $products = $productModel->getActiveProducts();

        // 2. Lấy danh mục
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->index();
        
        // 3. Lấy COUPON hợp lệ (QUAN TRỌNG: PHẢI CÓ DÒNG NÀY)
        $couponModel = $this->model('Coupon');
        $coupons = $couponModel->getValidCoupons();

        // Kiểm tra xem biến coupons có dữ liệu không (Debug nếu cần)
        // var_dump($coupons); die; 

        $this->view('clientviews.home.index', [
            'products' => $products,
            'categories' => $categories,
            'coupons' => $coupons // Truyền biến này sang View
        ]);
    }
}