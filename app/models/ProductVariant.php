<?php
class ProductVariant extends Model
{
    private $table = 'product_variants';

    // Lấy tất cả biến thể của một sản phẩm
    public function getByProductId($productId)
    {
        $sql = "SELECT * FROM $this->table WHERE product_id = :product_id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    // Xóa tất cả biến thể của sản phẩm (Dùng khi update hoặc delete sản phẩm)
    public function deleteByProductId($productId)
    {
        $sql = "DELETE FROM $this->table WHERE product_id = :product_id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['product_id' => $productId]);
    }
}