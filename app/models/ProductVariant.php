<?php
class ProductVariant extends Model
{
    private $table = 'product_variants';

    // Thêm biến thể mới
    public function create($data)
    {
        $sql = "INSERT INTO $this->table (product_id, size, color, quantity, image) 
                VALUES (:product_id, :size, :color, :quantity, :image)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'product_id' => $data['product_id'],
            'size'       => $data['size'],
            'color'      => $data['color'],
            'quantity'   => $data['quantity'],
            'image'      => $data['image'] ?? null
        ]);
    }

    public function deleteByProductId($productId)
    {
        $sql = "DELETE FROM $this->table WHERE product_id = :product_id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['product_id' => $productId]);
    }
    
    public function find($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM product_variants WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByProductId($productId) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM product_variants WHERE product_id = :product_id");
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- LOGIC KHO HÀNG CHO BIẾN THỂ (UPDATE PRO) ---

    // Trừ kho biến thể
    public function decreaseStock($id, $quantity) {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE product_variants SET quantity = quantity - :qty WHERE id = :id AND quantity >= :qty");
        $stmt->execute([':qty' => $quantity, ':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    // Hoàn kho biến thể
    public function increaseStock($id, $quantity) {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE product_variants SET quantity = quantity + :qty WHERE id = :id");
        return $stmt->execute([':qty' => $quantity, ':id' => $id]);
    }
}