<?php
namespace App\Controllers\Admin;

use Controller;

class CategoryController extends Controller
{
    public function index()
    {
        // --- FIX LỖI CACHE ---
        // Vẫn giữ đoạn này để đảm bảo code nhận diện đúng thư mục view mới
        $cachePath = __DIR__ . '/../../storage/cache'; 
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) @unlink($file); 
            }
        }
        // ---------------------

        $categoryModel = $this->model('Category');
        $categories = $categoryModel->index(); 
        
        $this->view('adminviews.category.index', ['categories' => $categories]);
    }

    public function create()
    {
        $this->view('adminviews.category.create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // SỬA: Lấy đúng tên input từ form (create.blade.php dùng 'tendanhmuc')
            $name = $_POST['tendanhmuc'] ?? '';
            $image = '';

            // XỬ LÝ UPLOAD ẢNH
            if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
                // Đường dẫn lưu: app/public/uploads/categories
                $uploadDir = __DIR__ . '/../../public/uploads/categories/';
                
                // Tạo thư mục nếu chưa có
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Tạo tên file ngẫu nhiên để tránh trùng
                $fileName = time() . '_' . basename($_FILES['hinhanh']['name']);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $targetPath)) {
                    $image = $fileName;
                }
            }
            
            $categoryModel = $this->model('Category');
            // Truyền đúng key 'name' và 'image' mà Model yêu cầu
            $categoryModel->create(['name' => $name, 'image' => $image]);

            header('Location: /admin/category');
            exit;
        }
    }

    public function edit($id)
    {
        $categoryModel = $this->model('Category');
        $category = $categoryModel->show($id);
        
        $this->view('adminviews.category.edit', ['category' => $category]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // SỬA: Lấy đúng tên input (tendanhmuc)
            $name = $_POST['tendanhmuc'] ?? '';
            $data = ['name' => $name];

            // XỬ LÝ UPLOAD ẢNH (Nếu có chọn ảnh mới)
            if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/categories/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $fileName = time() . '_' . basename($_FILES['hinhanh']['name']);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $targetPath)) {
                    // Chỉ thêm key 'image' nếu upload thành công
                    $data['image'] = $fileName;
                }
            }

            $categoryModel = $this->model('Category');
            $categoryModel->update($id, $data);

            header('Location: /admin/category');
            exit;
        }
    }

    public function delete($id)
    {
        $categoryModel = $this->model('Category');
        $categoryModel->delete($id);

        header('Location: /admin/category');
        exit;
    }
}