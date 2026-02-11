<?php
namespace App\Controllers\Admin;

use Controller;

class UserController extends Controller
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

        $users = $this->model('User')->index();
        
        $this->view('adminviews.user.index', [
            'users' => $users, 
            'title' => 'Quản lý Người dùng'
        ]);
    }

    public function create()
    {
        $this->view('adminviews.user.create', ['title' => 'Thêm Người dùng mới']);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            
            if ($this->model('User')->findByEmail($email)) {
                echo "<script>alert('Email này đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $data = [
                'fullname' => $_POST['fullname'] ?? '',
                'email' => $email,
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'role' => $_POST['role'] ?? 0
            ];

            $this->model('User')->create($data);
            
            header('Location: /admin/user');
            exit();
        }
    }

    public function edit($id)
    {
        $user = $this->model('User')->show($id);
        
        if (!$user) {
            header('Location: /admin/user');
            exit;
        }

        $this->view('adminviews.user.edit', [
            'user' => $user, 
            'title' => 'Sửa Người dùng'
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role = $_POST['role'] ?? 0;
            
            $data = [
                'fullname' => $_POST['fullname'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'role' => $role,
                'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null
            ];

            $this->model('User')->update($id, $data);

            // --- QUAN TRỌNG: Cập nhật Session ngay lập tức ---
            // Nếu admin đang sửa chính tài khoản mình đang đăng nhập
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $id) {
                $_SESSION['user']['fullname'] = $data['fullname'];
                $_SESSION['user']['phone'] = $data['phone'];
                $_SESSION['user']['role'] = $role; // Cập nhật quyền mới vào session ngay
            }
            
            header('Location: /admin/user');
            exit();
        }
    }

    public function delete($id)
    {
        $userModel = $this->model('User');
        $user = $userModel->show($id);

        // --- BẢO VỆ: Không cho phép xóa Admin ---
        // Kiểm tra nếu role là 1 hoặc 'admin'
        if ($user && ($user['role'] == 1 || $user['role'] === 'admin')) {
            echo "<script>
                alert('CẢNH BÁO: Không thể xóa tài khoản Quản trị viên (Admin)! Chỉ được phép xóa tài khoản Khách hàng.');
                window.location.href = '/admin/user';
            </script>";
            exit;
        }

        $userModel->delete($id);
        
        header('Location: /admin/user');
        exit();
    }
}