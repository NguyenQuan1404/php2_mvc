<?php
class Product extends Model
{
    private $table = 'products';

    // --- ADMIN METHODS ---
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

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (name, category_id, brand_id, price, sale_price, quantity, image, description, short_description, status) 
                VALUES (:name, :category_id, :brand_id, :price, :sale_price, :quantity, :image, :description, :short_description, :status)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);

        $result = $stmt->execute([
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'],
            'quantity' => $data['quantity'],
            'image' => $data['image'],
            'description' => $data['description'],
            'short_description' => $data['short_description'],
            'status' => $data['status']
        ]);

        if ($result) {
            return $conn->lastInsertId();
        }
        return false;
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
        $params = [
            'name' => $data['name'],
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'],
            'quantity' => $data['quantity'],
            'description' => $data['description'],
            'short_description' => $data['short_description'],
            'status' => $data['status'],
            'id' => $id
        ];
        if (!empty($data['image'])) $params['image'] = $data['image'];
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // --- CLIENT METHODS ---
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

    public function getDetail($id)
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM $this->table p
                LEFT JOIN category c ON p.category_id = c.id
                LEFT JOIN brands b ON p.brand_id = b.id
                WHERE p.id = :id AND p.status = 1";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRelatedProducts($categoryId, $excludeId)
    {
        $sql = "SELECT * FROM $this->table 
                WHERE category_id = :cid AND id != :eid AND status = 1 
                ORDER BY rand() LIMIT 4";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['cid' => $categoryId, 'eid' => $excludeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilteredProducts($filters = [])
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM {$this->table} p 
                LEFT JOIN category c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE 1=1";

        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%" . $filters['keyword'] . "%";
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category_id'];
        }
        if (!empty($filters['brand_id'])) {
            $sql .= " AND p.brand_id = ?";
            $params[] = $filters['brand_id'];
        }
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }

        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc': $sql .= " ORDER BY p.price ASC"; break;
                case 'price_desc': $sql .= " ORDER BY p.price DESC"; break;
                case 'name_asc': $sql .= " ORDER BY p.name ASC"; break;
                case 'name_desc': $sql .= " ORDER BY p.name DESC"; break;
                default: $sql .= " ORDER BY p.id DESC"; break;
            }
        } else {
            $sql .= " ORDER BY p.id DESC";
        }

        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getListByIds($ids)
    {
        if (empty($ids)) return [];
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM {$this->table} p 
                LEFT JOIN category c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE p.id IN ($placeholders)";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_values($ids));
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $conn = $this->connect();
        $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- LOGIC KHO HÀNG CHUYÊN NGHIỆP (PRO STOCK) ---

    // 1. Trừ kho an toàn (Atomic Update) - Trả về false nếu không đủ hàng
    public function decreaseStock($id, $quantity) {
        $conn = $this->connect();
        // Điều kiện AND quantity >= :qty cực quan trọng để tránh âm kho
        $stmt = $conn->prepare("UPDATE products SET quantity = quantity - :qty WHERE id = :id AND quantity >= :qty");
        $stmt->execute([':qty' => $quantity, ':id' => $id]);
        return $stmt->rowCount() > 0; 
    }

    // 2. Hoàn kho (Khi hủy đơn)
    public function increaseStock($id, $quantity) {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE products SET quantity = quantity + :qty WHERE id = :id");
        return $stmt->execute([':qty' => $quantity, ':id' => $id]);
    }
}