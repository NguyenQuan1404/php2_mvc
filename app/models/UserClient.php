<?php

namespace App\Models;

use App\Core\Model;

class UserClient extends Model
{
    // Giả sử tên bảng là 'users', hãy đổi lại nếu bảng của bạn tên khác
    protected $table = 'users'; 

    public function create($data)
    {
        return $this->insert($data);
    }

    public function findByEmail($email)
    {
        // Giả sử cột email tên là 'email'
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        return $this->query($sql, [$email])->fetch(); // Giả sử method query trả về statement
    }

    // Lưu token reset password
    public function saveResetToken($email, $token)
    {
        // Token hết hạn sau 30 phút
        $expire = date('Y-m-d H:i:s', time() + 1800); 
        
        $sql = "UPDATE {$this->table} SET reset_token = ?, reset_token_expire = ? WHERE email = ?";
        return $this->query($sql, [$token, $expire, $email]);
    }

    // Kiểm tra token có hợp lệ không
    public function verifyToken($token)
    {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM {$this->table} WHERE reset_token = ? AND reset_token_expire > ? LIMIT 1";
        return $this->query($sql, [$token, $now])->fetch();
    }

    // Cập nhật mật khẩu mới và xóa token
    public function updateNewPassword($email, $newPasswordHash)
    {
        $sql = "UPDATE {$this->table} SET password = ?, reset_token = NULL, reset_token_expire = NULL WHERE email = ?";
        return $this->query($sql, [$newPasswordHash, $email]);
    }
}