<?php
namespace App\Controllers\Client;

use Controller;

// Đường dẫn file Mailer
$pathMailer = __DIR__ . '/../../Services/Mailler.php';
if (file_exists($pathMailer)) {
    require_once $pathMailer;
}

class AuthController extends Controller
{
    // --- ĐĂNG NHẬP ---
    public function login()
    {
        if (isset($_SESSION['user'])) {
            $role = $_SESSION['user']['role'] ?? 0;
            if ($role == 1 || $role === 'admin') {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /');
            }
            exit;
        }
        
        // SỬA: Trỏ vào thư mục 'clientviews'
        $this->view('clientviews.auth.login');
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('UserClient');
            $user = $userModel->authenticate($email, $password);

            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'phone' => $user['phone']
                ];

                $role = $user['role'];
                if ($role == 1 || $role === 'admin') {
                    header('Location: /admin/dashboard');
                } else {
                    header('Location: /');
                }
                exit;
            } else {
                $error = "Email hoặc mật khẩu không đúng!";
                // SỬA: Trỏ vào thư mục 'clientviews'
                $this->view('clientviews.auth.login', ['error' => $error, 'email' => $email]);
            }
        } else {
            header('Location: /auth/login');
            exit;
        }
    }

    // --- ĐĂNG KÝ ---
    public function register()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        // SỬA: Trỏ vào thư mục 'clientviews'
        $this->view('clientviews.auth.register');
    }

    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $phone = $_POST['phone'] ?? '';

            if ($password !== $confirm_password) {
                $this->view('clientviews.auth.register', [
                    'error' => 'Mật khẩu xác nhận không khớp!',
                    'fullname' => $fullname,
                    'email' => $email
                ]);
                return;
            }

            $userModel = $this->model('UserClient');

            if ($userModel->findByEmail($email)) {
                $this->view('clientviews.auth.register', [
                    'error' => 'Email này đã được sử dụng!',
                    'fullname' => $fullname
                ]);
                return;
            }

            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'phone' => $phone,
                'address' => '',
                'role' => 0 
            ];

            if ($userModel->create($data)) {
                header('Location: /auth/login?msg=registered');
                exit;
            } else {
                $this->view('clientviews.auth.register', ['error' => 'Lỗi hệ thống, vui lòng thử lại sau!']);
            }
        }
    }

    // --- QUÊN MẬT KHẨU ---
    public function forgotPassword()
    {
        // SỬA: Trỏ vào thư mục 'clientviews'
        $this->view('clientviews.auth.forgot');
    }

    public function handleForgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $userModel = $this->model('UserClient');
            $user = $userModel->findByEmail($email);

            if ($user) {
                $otp = rand(100000, 999999);
                $userModel->saveResetToken($email, $otp);

                $subject = "Mã OTP xác thực quên mật khẩu";
                $body = "
                    <h3>Chào {$user['fullname']},</h3>
                    <p>Mã xác thực (OTP) để đặt lại mật khẩu của bạn là:</p>
                    <h2 style='color: #d9534f; letter-spacing: 5px;'>$otp</h2>
                    <p>Mã này có hiệu lực trong 15 phút.</p>
                ";

                if (class_exists('Mailler')) {
                    \Mailler::send($email, $subject, $body);
                }

                $_SESSION['success'] = "Đã gửi mã OTP vào email. Vui lòng kiểm tra.";
                header("Location: /auth/resetPassword?email=$email");
                exit;
            } else {
                $_SESSION['error'] = 'Email không tồn tại trong hệ thống!';
                header('Location: /auth/forgotPassword');
                exit;
            }
        }
    }

    // --- ĐẶT LẠI MẬT KHẨU ---
    public function resetPassword()
    {
        $email = $_GET['email'] ?? '';
        // SỬA: Trỏ vào thư mục 'clientviews'
        $this->view('clientviews.auth.reset', ['email' => $email]);
    }

    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $otp = $_POST['otp'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
                header("Location: /auth/resetPassword?email=$email");
                exit;
            }

            $userModel = $this->model('UserClient');
            $user = $userModel->checkUserOtp($email, $otp);

            if (!$user) {
                $_SESSION['error'] = "Mã OTP không đúng hoặc đã hết hạn!";
                header("Location: /auth/resetPassword?email=$email");
                exit;
            }

            if ($userModel->resetPassword($email, $password)) {
                $_SESSION['success'] = "Đổi mật khẩu thành công! Hãy đăng nhập.";
                header('Location: /auth/login');
                exit;
            } else {
                $_SESSION['error'] = "Lỗi hệ thống!";
                header("Location: /auth/resetPassword?email=$email");
                exit;
            }
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /auth/login');
        exit;
    }
}