<?php
class Product extends Model
{
    private $table = 'products';

    // --- ADMIN METHODS (Giữ nguyên) ---
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

    // Lấy sản phẩm active cho trang chủ
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

    // Lấy chi tiết 1 sản phẩm (Kèm tên Brand, Category)
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

    // Lấy sản phẩm liên quan (cùng danh mục, trừ chính nó)
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
        // Đã đổi LEFT JOIN categories thành category cho đúng DB của sếp
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM {$this->table} p 
                LEFT JOIN category c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE 1=1";

        $params = [];

        // Lọc theo từ khóa tìm kiếm
        if (!empty($filters['keyword'])) {
            $sql .= " AND p.name LIKE ?";
            $params[] = "%" . $filters['keyword'] . "%";
        }

        // Lọc theo danh mục
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category_id'];
        }

        // Lọc theo thương hiệu
        if (!empty($filters['brand_id'])) {
            $sql .= " AND p.brand_id = ?";
            $params[] = $filters['brand_id'];
        }

        // Lọc theo khoảng giá
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }

        // Sắp xếp
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $sql .= " ORDER BY p.price ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY p.price DESC";
                    break;
                case 'name_asc':
                    $sql .= " ORDER BY p.name ASC";
                    break;
                case 'name_desc':
                    $sql .= " ORDER BY p.name DESC";
                    break;
                default:
                    $sql .= " ORDER BY p.id DESC"; // Mới nhất
                    break;
            }
        } else {
            $sql .= " ORDER BY p.id DESC";
        }

        // BẢN FIX NẰM Ở ĐÂY: Sử dụng $this->connect() theo chuẩn MVC của sếp
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    // Hàm lấy danh sách sản phẩm dựa vào mảng ID (Dùng cho Đã xem, So sánh, Yêu thích)
    public function getListByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }

        // Tạo chuỗi dấu hỏi chấm (?, ?, ?) tương ứng với số lượng ID
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        // Truy vấn lấy sản phẩm kèm tên danh mục và thương hiệu
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM {$this->table} p 
                LEFT JOIN category c ON p.category_id = c.id 
                LEFT JOIN brands b ON p.brand_id = b.id 
                WHERE p.id IN ($placeholders)";
                
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        
        // Truyền mảng ID vào để execute
        $stmt->execute(array_values($ids));
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
