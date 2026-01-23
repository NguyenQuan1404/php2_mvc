<?php
class Product extends Model
{
    private $table = 'products';


    public function index()
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM $this->table p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                ORDER BY p.id DESC";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveProducts()
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM $this->table p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.status = 1 
                ORDER BY p.id DESC";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (name, category_id, brand_id, price, sale_price, quantity, image, description, short_description, status) 
                VALUES (:name, :category_id, :brand_id, :price, :sale_price, :quantity, :image, :description, :short_description, :status)";
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

    public function update($id, $data)
    {
        $imageSql = !empty($data['image']) ? ", image = :image" : "";
        $sql = "UPDATE $this->table SET 
                name = :name, category_id = :category_id, brand_id = :brand_id, 
                price = :price, sale_price = :sale_price, quantity = :quantity, 
                description = :description, short_description = :short_description, status = :status
                $imageSql
                WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $data['id'] = $id;
        if (empty($data['image'])) unset($data['image']);
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