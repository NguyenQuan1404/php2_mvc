<?php
class BrandController extends Controller
{
    public function index()
    {
        $brands = $this->model('Brand')->index();
        $this->view('brand/index', ['brands' => $brands, 'title' => 'Quản lý Thương hiệu']);
    }

    public function create()
    {
        $this->view('brand/create', ['title' => 'Thêm Thương hiệu mới']);
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

            $this->model('Brand')->create(['name' => $name, 'description' => $description]);
            header('Location: /brand');
            exit();
        }
    }

    public function edit($id)
    {
        $brand = $this->model('Brand')->show($id);
        $this->view('brand/edit', ['brand' => $brand, 'title' => 'Sửa Thương hiệu']);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            $this->model('Brand')->update($id, ['name' => $name, 'description' => $description]);
            header('Location: /brand');
            exit();
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model('Brand')->delete($id);
            header('Location: /brand');
            exit();
        }
    }
}