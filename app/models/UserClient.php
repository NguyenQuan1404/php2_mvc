<?php
class UserClient extends Model
{
    private $table = 'users';

    // Đăng nhập: Kiểm tra email và password
    public function authenticate($email, $password)
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $role = $data['role'] ?? 0;
        
        $sql = "INSERT INTO $this->table (fullname, email, password, phone, address, role) 
                VALUES (:fullname, :email, :password, :phone, :address, :role)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'role' => $role
        ]);
    }

    // Lưu mã OTP vào database
    public function saveResetToken($email, $token)
    {
        $sql = "UPDATE $this->table 
                SET reset_token = :token, 
                    reset_token_expire = DATE_ADD(NOW(), INTERVAL 15 MINUTE) 
                WHERE email = :email";
                
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'token' => $token,
            'email' => $email
        ]);
    }

    // Kiểm tra OTP
    public function checkUserOtp($email, $otp)
    {
        $sql = "SELECT * FROM $this->table 
                WHERE email = :email 
                AND reset_token = :otp 
                AND reset_token_expire > NOW()";
                
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'email' => $email, 
            'otp' => $otp
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đổi mật khẩu mới
    public function resetPassword($email, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE $this->table 
                SET password = :password, reset_token = NULL, reset_token_expire = NULL 
                WHERE email = :email";
                
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'password' => $hashedPassword,
            'email' => $email
        ]);
    }
}