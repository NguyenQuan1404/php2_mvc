<?php

class OrderDetail extends Model {
    protected $table = 'order_details';

    // Đã thêm cột variant_id vào insert
    public function create($data) {
        $conn = $this->connect();
        $sql = "INSERT INTO order_details (order_id, product_id, variant_id, product_name, price, quantity, total) 
                VALUES (:order_id, :product_id, :variant_id, :product_name, :price, :quantity, :total)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':order_id' => $data['order_id'],
            ':product_id' => $data['product_id'],
            ':variant_id' => $data['variant_id'] ?? null, // Hỗ trợ lưu variant
            ':product_name' => $data['product_name'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':total' => $data['total']
        ]);
    }

    public function getItemsByOrderId($orderId) {
        $conn = $this->connect();
        $stmt = $conn->prepare("
            SELECT od.*, p.image as product_image 
            FROM order_details od
            LEFT JOIN products p ON od.product_id = p.id
            WHERE od.order_id = :order_id
        ");
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}