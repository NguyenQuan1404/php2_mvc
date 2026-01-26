<?php
class DashboardController extends Controller
{
    public function index()
    {
        // Lấy số liệu thống kê để hiển thị ra Dashboard cho đẹp
        $productCount = count($this->model('Product')->index());
        $categoryCount = count($this->model('Category')->index());
        $brandCount = count($this->model('Brand')->index());
        $userCount = count($this->model('User')->index());

        $this->view('dashboard/index', [
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