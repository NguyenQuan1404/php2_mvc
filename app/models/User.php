<?php
class User extends Model {
    protected $table = 'users';

    // --- CÁC HÀM CƠ BẢN ---
    public function find($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Dùng cho Admin quản lý
    public function index() {
        $sql = "SELECT * FROM $this->table ORDER BY id DESC";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO $this->table (fullname, email, password, phone, address, role) 
                VALUES (:fullname, :email, :password, :phone, :address, :role)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- CÁC HÀM DÀNH CHO CLIENT PROFILE ---

    // Cập nhật thông tin cá nhân
    public function updateProfile($id, $data) {
        $conn = $this->connect();
        $sql = "UPDATE users SET fullname = :fullname, phone = :phone, address = :address WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':fullname' => $data['fullname'],
            ':phone' => $data['phone'],
            ':address' => $data['address'],
            ':id' => $id
        ]);
    }

    // Cập nhật mật khẩu
    public function updatePassword($id, $newPasswordHash) {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute([
            ':password' => $newPasswordHash,
            ':id' => $id
        ]);
    }

    // Kiểm tra mật khẩu cũ (để đổi pass)
    public function checkPassword($id, $passwordInput) {
        $user = $this->find($id);
        if ($user && password_verify($passwordInput, $user['password'])) {
            return true;
        }
        return false;
    }

    // Admin Update (Hỗ trợ controller admin cũ)
    public function update($id, $data) {
        $passSql = !empty($data['password']) ? ", password = :password" : "";
        $sql = "UPDATE $this->table SET fullname = :fullname, phone = :phone, address = :address, role = :role $passSql WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $data['id'] = $id;
        if (empty($data['password'])) unset($data['password']); 
        return $stmt->execute($data);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    public function show($id) { return $this->find($id); }
}