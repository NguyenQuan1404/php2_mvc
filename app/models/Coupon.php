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
}