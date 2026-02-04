<?php
namespace App\Controllers\Admin;

use Controller;

class CategoryController extends Controller
{
    public function index()
    {
        // --- ĐOẠN CODE FIX LỖI (TỰ ĐỘNG XÓA CACHE & DEBUG) ---
        
        // 1. Tự động dọn dẹp Cache cũ
        $cachePath = __DIR__ . '/../../storage/cache'; // Đường dẫn tương đối từ Controllers/Admin
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) unlink($file); 
            }
        }

        // 2. Kiểm tra đường dẫn View thực tế
        // Lấy VIEW_PATH từ hằng số hoặc tính toán thủ công
        $viewPath = defined('VIEW_PATH') ? VIEW_PATH : realpath(__DIR__ . '/../../views');
        $targetFolder = $viewPath . '/adminviews/category';
        $targetFile = $targetFolder . '/index.blade.php';

        if (!file_exists($targetFile)) {
            echo "<div style='font-family: sans-serif; background: #fff3cd; color: #856404; padding: 20px; border: 2px solid #ffeeba; margin: 20px;'>";
            echo "<h2 style='color: red; margin-top: 0;'>🔥 VẪN KHÔNG TÌM THẤY VIEW!</h2>";
            echo "<p>Hệ thống đã tự động xóa cache, nhưng vẫn không thấy file view.</p>";
            echo "<hr>";
            echo "<strong>1. Hệ thống đang tìm file tại đây:</strong><br><code style='background:#eee; padding:5px; display:block; margin:5px 0;'>$targetFile</code>";
            
            echo "<br><strong>2. Kiểm tra thư mục 'adminviews':</strong><br>";
            $adminViewPath = $viewPath . '/adminviews';
            if (!is_dir($adminViewPath)) {
                echo "<span style='color: red'>❌ Thư mục <b>adminviews</b> KHÔNG tồn tại trong <b>app/views</b>!</span>";
                echo "<br>Danh sách thư mục đang có trong app/views:<pre>" . print_r(scandir($viewPath), true) . "</pre>";
            } else {
                echo "<span style='color: green'>✅ Thư mục <b>adminviews</b> có tồn tại.</span>";
                
                echo "<br><br><strong>3. Kiểm tra bên trong 'adminviews':</strong><br>";
                $subDirs = scandir($adminViewPath);
                echo "Các thư mục con tìm thấy:<pre>" . print_r($subDirs, true) . "</pre>";
                
                if (!in_array('category', $subDirs) && in_array('Category', $subDirs)) {
                    echo "<h3 style='color: blue'>💡 PHÁT HIỆN: Bạn đặt tên thư mục là 'Category' (viết hoa), hãy sửa code thành 'adminviews.Category.index' hoặc đổi tên thư mục thành thường.</h3>";
                }
            }
            echo "</div>";
            die(); // Dừng code để bạn đọc thông báo
        }
        // --- KẾT THÚC ĐOẠN DEBUG ---


        $categoryModel = $this->model('Category');
        
        // Gọi hàm index() thay vì all()
        $categories = $categoryModel->index(); 
        
        // Trỏ vào thư mục 'adminviews' -> 'category' -> 'index.blade.php'
        $this->view('adminviews.category.index', ['categories' => $categories]);
    }

    public function create()
    {
        // Trỏ vào thư mục 'adminviews'
        $this->view('adminviews.category.create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            // Xử lý upload ảnh (nếu có) tại đây
            
            $categoryModel = $this->model('Category');
            $categoryModel->create(['name' => $name]);

            header('Location: /admin/category');
            exit;
        }
    }

    public function edit($id)
    {
        $categoryModel = $this->model('Category');
        
        // Gọi hàm show($id)
        $category = $categoryModel->show($id);
        
        // Trỏ vào thư mục 'adminviews'
        $this->view('adminviews.category.edit', ['category' => $category]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            
            $categoryModel = $this->model('Category');
            $categoryModel->update($id, ['name' => $name]);

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