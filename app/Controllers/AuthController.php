<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\LoginClient; // Sử dụng đúng Model bạn yêu cầu

class AuthController extends Controller
{
    private $loginClientModel;

    public function __construct()
    {
        $this->loginClientModel = new LoginClient();
    }

    // --- ĐĂNG NHẬP ---
    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        $this->view('auth.login');
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->loginClientModel->authenticate($email, $password);

            if ($user) {
                // Lưu session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role'], 
                    'avatar' => $user['avatar'] ?? null
                ];

                // Phân quyền chuyển hướng
                if ($user['role'] == 1) {
                    header('Location: /dashboard');
                } else {
                    header('Location: /');
                }
                exit;
            } else {
                $error = "Email hoặc mật khẩu không đúng!";
                $this->view('auth.login', ['error' => $error, 'email' => $email]);
            }
        }
    }

    // --- ĐĂNG KÝ ---
    public function register()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        $this->view('auth.register');
    }

    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                $this->view('auth.register', [
                    'error' => 'Mật khẩu xác nhận không khớp!', 
                    'fullname' => $fullname, 
                    'email' => $email
                ]);
                return;
            }

            // Check email tồn tại
            if ($this->loginClientModel->findByEmail($email)) {
                $this->view('auth.register', [
                    'error' => 'Email này đã được sử dụng!', 
                    'fullname' => $fullname
                ]);
                return;
            }

            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 0, // Mặc định User
                'status' => 1
            ];

            if ($this->loginClientModel->create($data)) {
                header('Location: /auth/login?msg=registered');
                exit;
            } else {
                $this->view('auth.register', ['error' => 'Lỗi hệ thống, vui lòng thử lại sau!']);
            }
        }
    }

    // --- QUÊN MẬT KHẨU ---
    public function forgotPassword()
    {
        $this->view('auth.forgot');
    }

    public function handleForgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $user = $this->loginClientModel->findByEmail($email);

            if ($user) {
                $success = "Link khôi phục mật khẩu đã được gửi đến email của bạn!";
                $this->view('auth.forgot', ['success' => $success]);
            } else {
                $this->view('auth.forgot', ['error' => 'Email không tồn tại trong hệ thống!']);
            }
        }
    }

    // --- ĐĂNG XUẤT ---
    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /');
        exit;
    }
}