<?php
namespace App\Controllers\Admin;

use Controller;
class UserController extends Controller
{
    public function index()
    {
        $users = $this->model('User')->index();
        $this->view('user/index', ['users' => $users, 'title' => 'Quản lý Người dùng']);
    }

    public function create()
    {
        $this->view('user/create', ['title' => 'Thêm Người dùng mới']);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            

            if ($this->model('User')->findByEmail($email)) {
                echo "<script>alert('Email này đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $data = [
                'fullname' => $_POST['fullname'],
                'email' => $email,
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'role' => $_POST['role']
            ];

            $this->model('User')->create($data);
            header('Location: /user');
            exit();
        }
    }

    public function edit($id)
    {
        $user = $this->model('User')->show($id);
        $this->view('user/edit', ['user' => $user, 'title' => 'Sửa Người dùng']);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'fullname' => $_POST['fullname'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'role' => $_POST['role'],
                'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null
            ];

            $this->model('User')->update($id, $data);
            header('Location: /user');
            exit();
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model('User')->delete($id);
            header('Location: /user');
            exit();
        }
    }
}