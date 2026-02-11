<?php
namespace App\Controllers\Client;

use Controller;

class HomeController extends Controller
{
    public function index()
    {
        // --- FIX LỖI CACHE ---
        $cachePath = __DIR__ . '/../../storage/cache'; 
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) @unlink($file); 
            }
        }
        // ---------------------

        // 1. Gọi Model Product
        $productModel = $this->model('Product');
        
        // 2. Lấy sản phẩm active (đã fix trong Model)
        $products = $productModel->getActiveProducts();
        
        // 3. Lấy danh mục để hiển thị (nếu cần)
        $categories = $this->model('Category')->index();

        // 4. Gọi View theo cấu trúc chuẩn: clientviews.home.index
        // Điều này yêu cầu bạn phải di chuyển file view vào: app/views/clientviews/home/index.blade.php
        $this->view('clientviews.home.index', [
            'products' => $products,
            'categories' => $categories,
            'title' => 'Trang chủ - Vua Bóng Đá'
        ]);
    }
}