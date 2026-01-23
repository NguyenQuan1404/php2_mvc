<?php
class User extends Model
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
        $sql = "INSERT INTO $this->table (fullname, email, password, phone, address, role) 
                VALUES (:fullname, :email, :password, :phone, :address, :role)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function show($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}