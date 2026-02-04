<?php
class Category extends Model
{
    // Tôi để là 'categories' vì trong Database của bạn tên bảng là số nhiều
    // Nếu bảng thật là 'category', bạn sửa lại dòng này nhé.
    private $table = 'category';

    public function index()
    {
        $sql = "SELECT * FROM $this->table ORDER BY id DESC";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data = [])
    {
        // Kiểm tra xem có ảnh không
        if (!empty($data['image'])) {
            $sql = "INSERT INTO $this->table (name, image) VALUES (:name, :image)";
            $params = [
                'name' => $data['name'],
                'image' => $data['image']
            ];
        } else {
            $sql = "INSERT INTO $this->table (name) VALUES (:name)";
            $params = [
                'name' => $data['name']
            ];
        }

        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute($params);
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
        if (!empty($data['image'])) {
            $sql = "UPDATE $this->table SET name = :name, image = :image WHERE id = :id";
            $params = [
                'name' => $data['name'],
                'image' => $data['image'],
                'id' => $id
            ];
        } else {
            $sql = "UPDATE $this->table SET name = :name WHERE id = :id";
            $params = [
                'name' => $data['name'],
                'id' => $id
            ];
        }

        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}