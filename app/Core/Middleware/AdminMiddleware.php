<?php
require_once __DIR__ . '/BaseMiddleware.php';
require_once __DIR__ . '/AuthMiddleware.php';

class AdminMiddleware extends BaseMiddleware
{
    public function handle()
    {
        // 1. Chạy qua AuthMiddleware trước
        $auth = new AuthMiddleware();
        $auth->handle();

        // 2. Kiểm tra quyền Admin
        // Lấy role từ session
        $role = $_SESSION['user']['role'] ?? null;

        // SỬA LỖI: Chấp nhận cả số 1 HOẶC chuỗi "admin"
        // (Vì database/code login của bạn đang lưu chữ "admin")
        if ($role != 1 && strtolower($role) !== 'admin') {
            http_response_code(403);
            echo "<h1>403 Forbidden</h1>";
            echo "<p>Bạn không có quyền truy cập vào trang quản trị.</p>";
            echo "<a href='/'>Quay về trang chủ</a>";
            exit;
        }
    }
}