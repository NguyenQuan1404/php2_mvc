<?php

class Order extends Model {
    protected $table = 'orders';

    // Tạo đơn hàng mới
    public function create($data) {
        $conn = $this->connect();
        $sql = "INSERT INTO orders (user_id, fullname, email, phone, address, note, total_money, status, created_at) 
                VALUES (:user_id, :fullname, :email, :phone, :address, :note, :total_money, :status, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':fullname' => $data['fullname'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':address' => $data['address'],
            ':note' => $data['note'] ?? '',
            ':total_money' => $data['total_money'],
            ':status' => 'pending'
        ]);
        
        return $conn->lastInsertId();
    }

    // Lấy danh sách đơn hàng của user
    public function getOrdersByUserId($userId) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 đơn hàng
    public function find($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Admin: Lấy tất cả đơn hàng
    public function getAllOrders() {
        $conn = $this->connect();
        $stmt = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: Cập nhật trạng thái
    public function updateStatus($id, $status) {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }
}