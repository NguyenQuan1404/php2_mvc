<?php
class Coupon extends Model
{
    private $table = 'coupons';

    // Đổi tên thành index cho đồng bộ với các Model khác
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
        $sql = "INSERT INTO $this->table (code, type, value, min_order_value, quantity, start_date, end_date, status) 
                VALUES (:code, :type, :value, :min_order_value, :quantity, :start_date, :end_date, :status)";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'code' => $data['code'],
            'type' => $data['type'],
            'value' => $data['value'],
            'min_order_value' => $data['min_order_value'],
            'quantity' => $data['quantity'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status']
        ]);
    }

    // Thêm hàm show để lấy 1 dòng
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
        $sql = "UPDATE $this->table SET 
                code = :code, 
                type = :type, 
                value = :value, 
                min_order_value = :min_order_value, 
                quantity = :quantity, 
                start_date = :start_date, 
                end_date = :end_date, 
                status = :status 
                WHERE id = :id";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'code' => $data['code'],
            'type' => $data['type'],
            'value' => $data['value'],
            'min_order_value' => $data['min_order_value'],
            'quantity' => $data['quantity'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function checkCodeExists($code, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM $this->table WHERE code = :code";
        if ($excludeId) {
            $sql .= " AND id != :id";
        }
        
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        
        $params = ['code' => $code];
        if ($excludeId) {
            $params['id'] = $excludeId;
        }
        
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // --- CÁC HÀM BẮT BUỘC PHẢI CÓ CHO CLIENT ---

    public function getValidCoupons() {
        $today = date('Y-m-d');
        // Logic: Status = 1 (Active) AND Ngày bắt đầu <= Hôm nay AND Ngày kết thúc >= Hôm nay AND Số lượng > 0
        $sql = "SELECT * FROM $this->table 
                WHERE status = 1 
                AND start_date <= :today 
                AND end_date >= :today 
                AND quantity > 0
                ORDER BY end_date ASC";
        
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['today' => $today]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm mã giảm giá theo code (Dùng cho Checkout)
    public function findByCode($code) {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM $this->table 
                WHERE code = :code 
                AND status = 1 
                AND start_date <= :today 
                AND end_date >= :today 
                AND quantity > 0";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute(['code' => $code, 'today' => $today]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Trừ số lượng mã sau khi đặt hàng thành công
    public function decreaseQuantity($code) {
        $sql = "UPDATE $this->table SET quantity = quantity - 1 WHERE code = :code AND quantity > 0";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['code' => $code]);
    }
}