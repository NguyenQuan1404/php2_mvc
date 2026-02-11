<?php
namespace App\Controllers\Admin;

use Controller; 

class DashboardController extends Controller
{
    public function index()
    {
        // --- FIX LỖI CACHE TỰ ĐỘNG ---
        $cachePath = __DIR__ . '/../../storage/cache'; 
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) @unlink($file); 
            }
        }
        // -----------------------------

        // Lấy số liệu thống kê để hiển thị ra Dashboard
        // Sử dụng hàm index() mà các Model đã chuẩn hóa
        $productCount = count($this->model('Product')->index());
        $categoryCount = count($this->model('Category')->index());
        $brandCount = count($this->model('Brand')->index());
        $userCount = count($this->model('User')->index());

        // Trỏ vào thư mục: app/views/adminviews/dashboard/index.blade.php
        $this->view('adminviews.dashboard.index', [
            'title' => 'Tổng quan hệ thống',
            'stats' => [
                'products' => $productCount,
                'categories' => $categoryCount,
                'brands' => $brandCount,
                'users' => $userCount
            ]
        ]);
    }
}