<?php
// Không dùng namespace để router có thể new AuthController() trực tiếp
// use App\Core\Controller; // Core/Controller.php đã được autoload

class AuthController extends Controller
{
    // --- ĐĂNG NHẬP ---
    public function login()
    {
        // Nếu đã đăng nhập thì đá về trang chủ hoặc dashboard
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

            // Gọi model UserClient theo yêu cầu
            $userModel = $this->model('UserClient');
            $user = $userModel->authenticate($email, $password);

            if ($user) {
                // Lưu session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role'], 
                    'phone' => $user['phone']
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
        } else {
            // Nếu truy cập trực tiếp bằng GET thì về lại trang login
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

            // Validate cơ bản
            if ($password !== $confirm_password) {
                $this->view('auth.register', [
                    'error' => 'Mật khẩu xác nhận không khớp!', 
                    'fullname' => $fullname, 
                    'email' => $email
                ]);
                return;
            }

            $userModel = $this->model('UserClient');

            // Check email tồn tại
            if ($userModel->findByEmail($email)) {
                $this->view('auth.register', [
                    'error' => 'Email này đã được sử dụng!', 
                    'fullname' => $fullname
                ]);
                return;
            }

            // Tạo user mới
            $data = [
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'phone' => $phone,
                'address' => '',
                'role' => 0 // Mặc định User thường
            ];

            if ($userModel->create($data)) {
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
            $email = $_POST['email'] ?? '';
            $userModel = $this->model('UserClient');
            $user = $userModel->findByEmail($email);

            if ($user) {
                $success = "Nếu email tồn tại, chúng tôi đã gửi link khôi phục mật khẩu vào hòm thư của bạn!";
                $this->view('auth.forgot', ['success' => $success]);
            } else {
                $this->view('auth.forgot', ['error' => 'Email không tồn tại trong hệ thống!']);
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