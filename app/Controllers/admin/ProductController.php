<?php
namespace App\Controllers\Admin;

use Controller;

class ProductController extends Controller
{
    public function index()
    {
        // --- FIX LỖI CACHE ---
        $cachePath = __DIR__ . '/../../storage/cache'; 
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) @unlink($file); 
            }
        }
        // ---------------------

        $productModel = $this->model('Product');
        $products = $productModel->index();
        
        $this->view('adminviews.product.index', [
            'products' => $products, 
            'title' => 'Quản lý Sản phẩm'
        ]);
    }

    public function create()
    {
        $categories = $this->model('Category')->index();
        $brands = $this->model('Brand')->index();

        $this->view('adminviews.product.create', [
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

            // Tính tổng tồn kho từ biến thể (nếu có)
            if (!empty($variants)) {
                foreach ($variants as $variant) {
                    $totalQuantity += (int)$variant['quantity'];
                }
            } else {
                $totalQuantity = $_POST['quantity'] ?? 0;
            }

            // 1. Xử lý ảnh chính
            $mainImage = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $mainImage = $this->handleUpload($_FILES['image']);
            }

            // 2. Tạo dữ liệu Sản phẩm chính
            $data = [
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
                'price' => $_POST['price'],
                'sale_price' => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
                'quantity' => $totalQuantity,
                'image' => $mainImage,
                'description' => $_POST['description'] ?? '',
                'short_description' => $_POST['short_description'] ?? '',
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            $productModel = $this->model('Product');
            $productId = $productModel->create($data); // Hàm create cần trả về ID vừa tạo

            if ($productId) {
                // 3. Xử lý Biến thể (Variants)
                $variantModel = $this->model('ProductVariant');
                
                if (!empty($variants)) {
                    foreach ($variants as $index => $variant) {
                        // Upload ảnh riêng cho biến thể
                        $variantImage = $this->handleVariantImageUpload($index);

                        $variantData = [
                            'product_id' => $productId,
                            'size' => $variant['size'],
                            'color' => $variant['color'],
                            'quantity' => $variant['quantity'],
                            'image' => $variantImage
                        ];
                        $variantModel->create($variantData);
                    }
                }
            }

            header('Location: /admin/product');
            exit;
        }
    }

    public function edit($id)
    {
        $product = $this->model('Product')->show($id);
        $variants = $this->model('ProductVariant')->getByProductId($id);
        
        $categories = $this->model('Category')->index();
        $brands = $this->model('Brand')->index();

        $this->view('adminviews.product.edit', [
            'product' => $product,
            'variants' => $variants,
            'categories' => $categories,
            'brands' => $brands,
            'title' => 'Sửa Sản phẩm'
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $variants = $_POST['variants'] ?? [];
            $totalQuantity = 0;

            if (!empty($variants)) {
                foreach ($variants as $variant) {
                    $totalQuantity += (int)$variant['quantity'];
                }
            } else {
                $totalQuantity = $_POST['quantity'] ?? 0;
            }

            // 1. Cập nhật thông tin chính
            $data = [
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
                'price' => $_POST['price'],
                'sale_price' => !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0,
                'quantity' => $totalQuantity,
                'description' => $_POST['description'] ?? '',
                'short_description' => $_POST['short_description'] ?? '',
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            // Nếu có upload ảnh mới thì cập nhật
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $data['image'] = $this->handleUpload($_FILES['image']);
            }

            $this->model('Product')->update($id, $data);

            // 2. Xử lý Biến thể: Xóa cũ tạo mới (Cách đơn giản nhất)
            // Lưu ý: Cách này sẽ mất ảnh biến thể cũ nếu không xử lý kỹ. 
            // Để an toàn, ở đây tôi giả định xóa hết tạo lại. Nếu muốn giữ ảnh cũ phải logic phức tạp hơn.
            
            $variantModel = $this->model('ProductVariant');
            $variantModel->deleteByProductId($id);

            if (!empty($variants)) {
                foreach ($variants as $index => $variant) {
                    // Check upload ảnh mới
                    $variantImage = $this->handleVariantImageUpload($index);
                    
                    // Nếu không upload ảnh mới, lấy lại ảnh cũ từ hidden field (nếu có)
                    if (!$variantImage && isset($_POST['existing_variant_images'][$index])) {
                        $variantImage = $_POST['existing_variant_images'][$index];
                    }

                    $variantData = [
                        'product_id' => $id,
                        'size' => $variant['size'],
                        'color' => $variant['color'],
                        'quantity' => $variant['quantity'],
                        'image' => $variantImage
                    ];
                    $variantModel->create($variantData);
                }
            }

            header('Location: /admin/product');
            exit;
        }
    }

    public function delete($id)
    {
        // Xóa biến thể trước
        $this->model('ProductVariant')->deleteByProductId($id);
        // Xóa sản phẩm
        $this->model('Product')->delete($id);
        
        header('Location: /admin/product');
        exit;
    }

    // --- HELPER UPLOAD (Dùng đường dẫn tuyệt đối) ---
    private function handleUpload($file)
    {
        if (!isset($file['name']) || $file['error'] != 0) return null;
        
        // Dùng __DIR__ để trỏ về public/uploads/products
        $targetDir = __DIR__ . '/../../public/uploads/products/';
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . rand(1000, 9999) . '.' . $extension;
        $targetFile = $targetDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $fileName;
        }
        return null;
    }

    private function handleVariantImageUpload($index)
    {
        if (!isset($_FILES['variants']['name'][$index]['image'])) return null;
        if ($_FILES['variants']['error'][$index]['image'] != 0) return null;

        $targetDir = __DIR__ . '/../../public/uploads/products/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $tmpName = $_FILES['variants']['tmp_name'][$index]['image'];
        $originalName = $_FILES['variants']['name'][$index]['image'];
        
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = 'var_' . time() . '_' . $index . '_' . rand(100, 999) . '.' . $extension;
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFile)) {
            return $fileName;
        }
        return null;
    }
}