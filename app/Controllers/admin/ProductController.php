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
            
            $variants = $_POST['variants'] ?? [];
            $totalQuantity = 0;

            // Tính tổng tồn kho
            if (!empty($variants)) {
                foreach ($variants as $variant) {
                    $totalQuantity += (int)$variant['quantity'];
                }
            } else {
                $totalQuantity = $_POST['quantity'] ?? 0;
            }

            // 1. Tạo dữ liệu Sản phẩm chính
            $data = [
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'] ?: null,
                'price' => $_POST['price'],
                'sale_price' => $_POST['sale_price'],
                'quantity' => $totalQuantity,
                'description' => $_POST['description'],
                'short_description' => $_POST['short_description'],
                'status' => $_POST['status'] ?? 1,
                'image' => $this->handleUpload($_FILES['image'])
            ];

            $productModel = $this->model('Product');
            
            if ($productModel->create($data)) {
                $conn = Database::$connection ?: \Database::connect();
                $productId = $conn->lastInsertId();

                // 2. Lưu biến thể kèm ảnh
                if (!empty($variants) && $productId) {
                    $variantModel = $this->model('ProductVariant');
                    
                    foreach ($variants as $index => $v) {
                        if (!empty($v['size']) && !empty($v['color'])) {
                            // Xử lý upload ảnh cho từng biến thể
                            $variantImage = $this->handleVariantImageUpload($index);
                            
                            $variantModel->create([
                                'product_id' => $productId,
                                'size'       => $v['size'],
                                'color'      => $v['color'],
                                'quantity'   => $v['quantity'],
                                'image'      => $variantImage
                            ]);
                        }
                    }
                }
            }

            header('Location: /product');
            exit();
        }
    }

    public function edit($id)
    {
        $product = $this->model('Product')->show($id);
        $categories = $this->model('Category')->index();
        $brands = $this->model('Brand')->index();
        $variants = $this->model('ProductVariant')->getByProductId($id);

        $this->view('product/edit', [
            'title' => 'Sửa Sản phẩm',
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'variants' => $variants
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productModel = $this->model('Product');
            $currentProduct = $productModel->show($id);

            // Xử lý ảnh chính
            $newImage = $this->handleUpload($_FILES['image']);
            if (empty($newImage)) {
                $newImage = $currentProduct['image'];
            } elseif (!empty($currentProduct['image'])) {
                // Xóa ảnh cũ nếu có ảnh mới
                $oldPath = "uploads/products/" . $currentProduct['image'];
                if (file_exists($oldPath)) unlink($oldPath);
            }

            $variants = $_POST['variants'] ?? [];
            $totalQuantity = 0;
            
            if (!empty($variants)) {
                foreach ($variants as $variant) {
                    $totalQuantity += (int)$variant['quantity'];
                }
            } else {
                 $totalQuantity = $_POST['quantity'] ?? 0;
            }

            $data = [
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'brand_id' => $_POST['brand_id'] ?: null,
                'price' => $_POST['price'],
                'sale_price' => $_POST['sale_price'],
                'quantity' => $totalQuantity,
                'description' => $_POST['description'],
                'short_description' => $_POST['short_description'],
                'status' => $_POST['status'] ?? 0, // Mặc định là 0 nếu không check
                'image' => $newImage 
            ];

            $productModel->update($id, $data);

            // --- XỬ LÝ BIẾN THỂ ---
            $variantModel = $this->model('ProductVariant');
            
            // Lấy danh sách biến thể CŨ để xóa ảnh cũ trước khi xóa record
            $oldVariants = $variantModel->getByProductId($id);
            foreach ($oldVariants as $ov) {
                // Logic xóa ảnh biến thể cũ có thể đặt ở đây nếu muốn dọn dẹp triệt để
                // Tuy nhiên, nếu user giữ lại ảnh cũ thì cần cẩn thận.
                // Để đơn giản: ta sẽ xóa record, nhưng ảnh thì chỉ xóa nếu user upload ảnh mới đè lên?
                // Ở đây ta dùng cách: Reset toàn bộ biến thể và tạo lại.
            }
            $variantModel->deleteByProductId($id); 

            if (!empty($variants)) {
                foreach ($variants as $index => $v) {
                    if (!empty($v['size']) && !empty($v['color'])) {
                        
                        // 1. Kiểm tra xem có upload ảnh mới cho biến thể này không
                        $uploadedImage = $this->handleVariantImageUpload($index);
                        
                        // 2. Nếu không upload mới, kiểm tra xem có hidden input chứa tên ảnh cũ không
                        $finalImage = $uploadedImage;
                        if (empty($finalImage) && !empty($v['old_image'])) {
                            $finalImage = $v['old_image'];
                        }

                        $variantModel->create([
                            'product_id' => $id,
                            'size'       => $v['size'],
                            'color'      => $v['color'],
                            'quantity'   => $v['quantity'],
                            'image'      => $finalImage
                        ]);
                    }
                }
            }

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
            
            // Có thể thêm logic xóa ảnh của các biến thể con tại đây

            $productModel->delete($id);
            header('Location: /product');
        }
    }

    private function handleUpload($file)
    {
        if (!isset($file['name']) || $file['error'] != 0) return null;
        
        $targetDir = "uploads/products/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $targetFile = $targetDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) return $fileName;
        return null;
    }

    // Hàm mới: Xử lý upload ảnh trong mảng variants
    private function handleVariantImageUpload($index)
    {
        // Kiểm tra xem có file nào được upload ở index này không
        if (!isset($_FILES['variants']['name'][$index]['image'])) return null;
        if ($_FILES['variants']['error'][$index]['image'] != 0) return null;

        $targetDir = "uploads/products/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $tmpName = $_FILES['variants']['tmp_name'][$index]['image'];
        $originalName = $_FILES['variants']['name'][$index]['image'];
        
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = 'var_' . time() . '_' . $index . '_' . rand(100,999) . '.' . $extension;
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFile)) return $fileName;
        return null;
    }
}