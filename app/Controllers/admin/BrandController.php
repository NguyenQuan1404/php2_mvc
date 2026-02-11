<?php
namespace App\Controllers\Admin;

use Controller;

class BrandController extends Controller
{
    public function index()
    {
        // --- FIX LỖI CACHE TỰ ĐỘNG ---
        $cachePath = __DIR__ . '/../../storage/cache'; 
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) @unlink($file); 
            }
        }
        // -----------------------------

        // Gọi Model và lấy dữ liệu
        $brandModel = $this->model('Brand');
        $brands = $brandModel->index();

        // Trỏ vào thư mục adminviews/brand
        $this->view('adminviews.brand.index', [
            'brands' => $brands, 
            'title' => 'Quản lý Thương hiệu'
        ]);
    }

    public function create()
    {
        $this->view('adminviews.brand.create', ['title' => 'Thêm Thương hiệu mới']);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                echo "<script>alert('Tên thương hiệu không được để trống'); window.history.back();</script>";
                return;
            }

            $this->model('Brand')->create([
                'name' => $name, 
                'description' => $description
            ]);

            // Chuyển hướng về /admin/brand
            header('Location: /admin/brand');
            exit();
        }
    }

    public function edit($id)
    {
        $brandModel = $this->model('Brand');
        $brand = $brandModel->show($id);

        $this->view('adminviews.brand.edit', [
            'brand' => $brand, 
            'title' => 'Sửa Thương hiệu'
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            $this->model('Brand')->update($id, [
                'name' => $name, 
                'description' => $description
            ]);

            // Chuyển hướng về /admin/brand
            header('Location: /admin/brand');
            exit();
        }
    }

    public function delete($id)
    {
        $this->model('Brand')->delete($id);
        
        // Chuyển hướng về /admin/brand
        header('Location: /admin/brand');
        exit();
    }
}