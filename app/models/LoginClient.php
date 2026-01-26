<?php

namespace App\Models;

use App\Core\Model;

class LoginClient extends Model
{
    protected $table = 'clients'; // Giả sử tên bảng trong DB là clients

    // Tìm user theo email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    // Xác thực đăng nhập
    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    // Đăng ký tài khoản mới
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (fullname, email, password, role, status) 
                VALUES (:fullname, :email, :password, :role, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'], // 0: Khách, 1: Admin
            'status' => $data['status'] // 1: Hoạt động
        ]);
    }
}