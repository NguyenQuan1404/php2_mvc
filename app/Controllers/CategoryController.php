<?php
class CategoryController extends Controller
{
    public function index()
    {
        $data = $this->model('Category')->index();
        $this->view('category/index', [
            'category' => $data, 
            'title' => 'Quản lý Loại đế giày (Category)'
        ]);
    }

    public function create()
    {
        $this->view('category/create', ['title' => 'Thêm Loại đế mới']);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['tendanhmuc'] ?? '';
            $imageName = $this->handleUpload($_FILES['hinhanh']);

            if (empty($name)) {
                echo "<script>alert('Vui lòng nhập tên loại đế (VD: TF, FG)'); window.history.back();</script>";
                return;
            }

            $this->model('Category')->create([
                'name' => $name, 
                'image' => $imageName
            ]);
            
            header('Location: /category');
            exit();
        }
    }

    public function edit($id)
    {
        $category = $this->model('Category')->show($id);
        
        if (!$category) {
            echo "<script>alert('Không tìm thấy danh mục!'); location.href='/category';</script>";
            return;
        }

        $this->view('category/edit', [
            'category' => $category,
            'title' => 'Chỉnh sửa Loại đế: ' . $category['name']
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoryModel = $this->model('Category');
            
   
            $currentCategory = $categoryModel->show($id);
            if (!$currentCategory) {
                echo "<script>alert('Danh mục không tồn tại!'); location.href='/category';</script>";
                return;
            }

            $name = $_POST['tendanhmuc'] ?? '';
            $newImage = $this->handleUpload($_FILES['hinhanh']);

            if (empty($name)) {
                echo "<script>alert('Tên loại đế không được để trống'); window.history.back();</script>";
                return;
            }

            $updateData = ['name' => $name];
            

            if (!empty($newImage)) {
                $updateData['image'] = $newImage;


                $oldImagePath = "uploads/categories/" . $currentCategory['image'];
                if (!empty($currentCategory['image']) && file_exists($oldImagePath)) {
                    unlink($oldImagePath); 
                }
            }

            $categoryModel->update($id, $updateData);
            header('Location: /category');
            exit();
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoryModel = $this->model('Category');
            

            $currentCategory = $categoryModel->show($id);
            
            if ($currentCategory) {

                $imagePath = "uploads/categories/" . $currentCategory['image'];
                if (!empty($currentCategory['image']) && file_exists($imagePath)) {
                    unlink($imagePath);
                }


                $categoryModel->delete($id);
            }

            header('Location: /category');
            exit();
        }
    }


    private function handleUpload($file) 
    {
        if (!isset($file['name']) || $file['error'] != 0) {
            return ''; 
        }

        $targetDir = "uploads/categories/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;
        
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($imageFileType, $validExtensions)) {
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                return $fileName;
            }
        }
        
        return '';
    }
}