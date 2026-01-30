<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserClient;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserClient();
    }

    public function index()
    {
        $this->login();
    }

    public function login()
    {
        // Dự án dùng file .blade.php và có cache => Dùng dấu chấm
        $this->view('auth.login');
    }

    public function register()
    {
        $this->view('auth.register');
    }

    // --- CHỨC NĂNG QUÊN MẬT KHẨU ---

    public function forgot()
    {
        // View: app/views/auth/forgot.blade.php
        $this->view('auth.forgot');
    }

    public function sendReset()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/forgot');
            exit;
        }

        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            $_SESSION['error'] = 'Vui lòng nhập email.';
            header('Location: /auth/forgot');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->saveResetToken($email, $token);

            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'];
            $link = "$protocol://$host/auth/reset?token=" . $token . "&email=" . urlencode($email);

            $sent = $this->sendEmail($email, $user['name'] ?? 'Khách hàng', $link);

            if ($sent) {
                $_SESSION['success'] = 'Link đặt lại mật khẩu đã được gửi vào email.';
            } else {
                $_SESSION['error'] = 'Lỗi gửi mail. Vui lòng thử lại sau.';
            }
        } else {
            $_SESSION['error'] = 'Email này chưa được đăng ký.';
        }

        header('Location: /auth/forgot');
        exit;
    }

    public function reset()
    {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        $user = $this->userModel->verifyToken($token);

        // Kiểm tra user tồn tại và email khớp
        if (!$user || (is_array($user) ? $user['email'] : $user->email) !== $email) {
            $_SESSION['error'] = 'Link không hợp lệ hoặc đã hết hạn.';
            header('Location: /auth/login');
            exit;
        }

        // View: app/views/auth/reset.blade.php
        $this->view('auth.reset', ['token' => $token, 'email' => $email]);
    }

    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/login');
            exit;
        }

        $token = $_POST['token'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp.';
            header("Location: /auth/reset?token=$token&email=$email");
            exit;
        }

        $user = $this->userModel->verifyToken($token);

        if ($user) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->updateNewPassword($email, $hashedPassword);

            $_SESSION['success'] = 'Đổi mật khẩu thành công. Vui lòng đăng nhập.';
            header('Location: /auth/login');
        } else {
            $_SESSION['error'] = 'Phiên làm việc hết hạn.';
            header('Location: /auth/forgot');
        }
    }

    private function sendEmail($to, $name, $link)
    {
        // Nếu dòng dưới đây báo đỏ, bạn cần chạy: composer require phpmailer/phpmailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'quannlpk04078@gmail.com'; // Thay email của bạn
            $mail->Password   = 'hirv rdau aryy kckz';    // Thay mật khẩu ứng dụng
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('no-reply@shop.com', 'Shop Admin');
            $mail->addAddress($to, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Yêu cầu đặt lại mật khẩu';
            $mail->Body    = "Click vào đây để đổi mật khẩu: <a href='$link'>$link</a>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}