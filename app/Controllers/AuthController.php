<?php
// Không dùng namespace để router có thể new AuthController() trực tiếp
// use App\Core\Controller; // Core/Controller.php đã được autoload
$pathMailer =realpath(__DIR__ ."/../Services/Mailler.php");
require_once $pathMailer;


class AuthController extends Controller
{
    // --- ĐĂNG NHẬP ---
    public function login()
    {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == 1) {
                header('Location: /dashboard');
            } else {
                header('Location: /');
            }
            exit;
        }
        $this->view('auth.login');
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
        $this->view('auth.register');
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
                $this->view('auth.register', [
                    'error' => 'Mật khẩu xác nhận không khớp!',
                    'fullname' => $fullname,
                    'email' => $email
                ]);
                return;
            }

            $userModel = $this->model('UserClient');

            if ($userModel->findByEmail($email)) {
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
                'phone' => $phone,
                'address' => '',
                'role' => 0
            ];

            if ($userModel->create($data)) {
                header('Location: /auth/login?msg=registered');
                exit;
            } else {
                $this->view('auth.register', ['error' => 'Lỗi hệ thống, vui lòng thử lại sau!']);
            }
        }
    }

    // --- QUÊN MẬT KHẨU (Bước 1: Nhập email) ---
    public function forgotPassword()
    {
        $this->view('auth.forgot');
    }

    public function handleForgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $userModel = $this->model('UserClient');
            $user = $userModel->findByEmail($email);

            if ($user) {
                // 1. Tạo OTP 6 số ngẫu nhiên
                $otp = rand(100000, 999999);
                
                // 2. Lưu OTP vào DB
                $userModel->saveResetToken($email, $otp);

                // 3. Gửi OTP qua mail
                $subject = "Mã OTP xác thực quên mật khẩu";
                $body = "
                    <h3>Chào {$user['fullname']},</h3>
                    <p>Mã xác thực (OTP) để đặt lại mật khẩu của bạn là:</p>
                    <h2 style='color: #d9534f; letter-spacing: 5px;'>$otp</h2>
                    <p>Mã này có hiệu lực trong 15 phút. Tuyệt đối không chia sẻ mã này cho ai khác.</p>
                ";

                Mailler::send($email, $subject, $body);

                // 4. Chuyển hướng sang trang nhập OTP, truyền email qua URL
                $_SESSION['success'] = "Đã gửi mã OTP vào email. Vui lòng kiểm tra và nhập mã bên dưới.";
                header("Location: /auth/resetPassword?email=$email");
                exit;
            } else {
                $_SESSION['error'] = 'Email không tồn tại trong hệ thống!';
                header('Location: /auth/forgotPassword');
                exit;
            }
        }
    }

    // --- ĐẶT LẠI MẬT KHẨU (Bước 2: Nhập OTP & Pass mới) ---
    public function resetPassword()
    {
        // Lấy email từ URL để điền sẵn vào form (hoặc input hidden)
        $email = $_GET['email'] ?? '';
        
        // Hiển thị form nhập OTP và pass mới
        $this->view('auth.reset', [
            'email' => $email
        ]);
    }

    // --- XỬ LÝ CẬP NHẬT (Bước 3: Validate & Update) ---
    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $otp = $_POST['otp'] ?? ''; // Lấy OTP người dùng nhập
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // 1. Validate mật khẩu
            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
                header("Location: /auth/resetPassword?email=$email");
                exit;
            }

            // 2. Kiểm tra OTP
            $userModel = $this->model('UserClient');
            $user = $userModel->checkUserOtp($email, $otp); // Hàm mới check cả email + otp

            if (!$user) {
                $_SESSION['error'] = "Mã OTP không đúng hoặc đã hết hạn!";
                header("Location: /auth/resetPassword?email=$email");
                exit;
            }

            // 3. Đổi mật khẩu
            if ($userModel->resetPassword($email, $password)) {
                $_SESSION['success'] = "Đặt lại mật khẩu thành công! Hãy đăng nhập ngay.";
                header('Location: /auth/login');
                exit;
            } else {
                $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại!";
                header("Location: /auth/resetPassword?email=$email");
                exit;
            }
        }
    }

    // --- ĐĂNG XUẤT ---
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