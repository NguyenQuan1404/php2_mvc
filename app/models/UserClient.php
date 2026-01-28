<?php
class UserClient extends Model
{
    private $table = 'users';

    public function index()
    {
        $sql = "SELECT * FROM $this->table ORDER BY id DESC";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // Mặc định role là 0 (User) nếu không truyền vào
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

    public function findByEmail($email) {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hàm xác thực đăng nhập
    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    public function update($id, $data)
    {
        $passSql = !empty($data['password']) ? ", password = :password" : "";
        
        $sql = "UPDATE $this->table SET 
                fullname = :fullname, 
                phone = :phone, 
                address = :address, 
                role = :role 
                $passSql
                WHERE id = :id";
                
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        
        $data['id'] = $id;
        if (empty($data['password'])) {
            unset($data['password']); 
        }
        
        return $stmt->execute($data);
    }
}