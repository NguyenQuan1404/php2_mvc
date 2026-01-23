<?php
class ProductController extends Controller
{
    public function index()
    {
        $products = $this->model('Product')->index();
        $this->view('product/index', ['products' => $products, 'title' => 'Quản lý Sản phẩm']);
    }

    public function create()
    {
        // Lấy danh mục và thương hiệu để hiển thị dropdown
        $categories = $this->model('Category')->index();
        $brands = $this->model('Brand')->index();

        $this->view('product/create', [
            'title' => 'Thêm Sản phẩm',
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'] ?: null,
                'price' => $_POST['price'],
                'sale_price' => $_POST['sale_price'],
                'quantity' => $_POST['quantity'],
                'description' => $_POST['description'],
                'short_description' => $_POST['short_description'],
                'status' => $_POST['status'] ?? 1,
                'image' => $this->handleUpload($_FILES['image'])
            ];

            $this->model('Product')->create($data);
            header('Location: /product');
            exit();
        }
    }

    public function edit($id)
    {
        $product = $this->model('Product')->show($id);
        $categories = $this->model('Category')->index();
        $brands = $this->model('Brand')->index();

        $this->view('product/edit', [
            'title' => 'Sửa Sản phẩm',
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productModel = $this->model('Product');
            $currentProduct = $productModel->show($id);

            $newImage = $this->handleUpload($_FILES['image']);
            

            if (!empty($newImage) && !empty($currentProduct['image'])) {
                $oldPath = "uploads/products/" . $currentProduct['image'];
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $data = [
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'] ?: null,
                'price' => $_POST['price'],
                'sale_price' => $_POST['sale_price'],
                'quantity' => $_POST['quantity'],
                'description' => $_POST['description'],
                'short_description' => $_POST['short_description'],
                'status' => $_POST['status'] ?? 1,
                'image' => $newImage 
            ];

            $productModel->update($id, $data);
            header('Location: /product');
            exit();
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productModel = $this->model('Product');
            $product = $productModel->show($id);
            

            if (!empty($product['image'])) {
                $path = "uploads/products/" . $product['image'];
                if (file_exists($path)) unlink($path);
            }

            $productModel->delete($id);
            header('Location: /product');
        }
    }

    private function handleUpload($file)
    {
        if (!isset($file['name']) || $file['error'] != 0) return null;
        
        $targetDir = "uploads/products/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        
        $fileName = time() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) return $fileName;
        return null;
    }
}