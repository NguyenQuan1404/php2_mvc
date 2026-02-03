<?php
require_once 'BaseMiddleware.php';

class AuthMiddleware extends BaseMiddleware
{
    public function handle()
    {
        // Kiểm tra session đã start chưa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nếu chưa đăng nhập -> Đá về trang login
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit;
        }
    }
}