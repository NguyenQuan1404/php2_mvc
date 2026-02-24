<?php
namespace App\Controllers\Client;

use Controller;

class UserController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Bắt buộc đăng nhập
        if (!isset($_SESSION['user_client']) && !isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit;
        }
    }

    // 1. Hiển thị trang hồ sơ
    public function profile() {
        $currentUser = $_SESSION['user_client'] ?? $_SESSION['user'];
        $userModel = $this->model('User');
        
        // Lấy data mới nhất từ DB
        $user = $userModel->find($currentUser['id']);

        $this->view('clientviews.user.profile', [
            'user' => $user,
            'msg' => $_SESSION['msg'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ]);
        
        // Xóa flash message
        unset($_SESSION['msg']);
        unset($_SESSION['error']);
    }

    // 2. Xử lý cập nhật hồ sơ
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUser = $_SESSION['user_client'] ?? $_SESSION['user'];
            $id = $currentUser['id'];

            $data = [
                'fullname' => htmlspecialchars($_POST['fullname']),
                'phone' => htmlspecialchars($_POST['phone']),
                'address' => htmlspecialchars($_POST['address'])
            ];

            $userModel = $this->model('User');
            if ($userModel->updateProfile($id, $data)) {
                // Cập nhật lại Session để hiển thị đúng tên ngay lập tức
                $updatedUser = $userModel->find($id);
                
                if (isset($_SESSION['user_client'])) $_SESSION['user_client'] = $updatedUser;
                if (isset($_SESSION['user'])) $_SESSION['user'] = $updatedUser;

                $_SESSION['msg'] = "Cập nhật thông tin thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại.";
            }

            header('Location: /user/profile');
            exit;
        }
    }

    // 3. Hiển thị form đổi mật khẩu
    public function changePassword() {
        $this->view('clientviews.user.change_password', [
            'msg' => $_SESSION['msg'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ]);
        unset($_SESSION['msg']);
        unset($_SESSION['error']);
    }

    // 4. Xử lý đổi mật khẩu
    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUser = $_SESSION['user_client'] ?? $_SESSION['user'];
            $id = $currentUser['id'];

            $oldPass = $_POST['old_password'];
            $newPass = $_POST['new_password'];
            $confirmPass = $_POST['confirm_password'];

            $userModel = $this->model('User');

            // Validate
            if (!$userModel->checkPassword($id, $oldPass)) {
                $_SESSION['error'] = "Mật khẩu cũ không chính xác!";
                header('Location: /user/changePassword');
                exit;
            }

            if (strlen($newPass) < 6) {
                $_SESSION['error'] = "Mật khẩu mới phải có ít nhất 6 ký tự!";
                header('Location: /user/changePassword');
                exit;
            }

            if ($newPass !== $confirmPass) {
                $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
                header('Location: /user/changePassword');
                exit;
            }

            // Update
            $newPassHash = password_hash($newPass, PASSWORD_DEFAULT);
            if ($userModel->updatePassword($id, $newPassHash)) {
                $_SESSION['msg'] = "Đổi mật khẩu thành công!";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống!";
            }

            header('Location: /user/changePassword');
            exit;
        }
    }
}