<?php
namespace App\Controllers\Client;

use Controller;

class ProductController extends Controller
{
    // URL: /product (Trang danh sách tất cả sản phẩm)
    public function index()
    {
        // --- FIX LỖI CACHE TỰ ĐỘNG (Giống Admin) ---
        $cachePath = __DIR__ . '/../../storage/cache'; 
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*'); 
            foreach($files as $file){ 
                if(is_file($file)) @unlink($file); 
            }
        }
        // -------------------------------------------

        $productModel = $this->model('Product');
        $categoryModel = $this->model('Category');

        // Lấy danh sách sản phẩm active
        $products = $productModel->getActiveProducts();
        // Lấy danh mục để hiển thị lọc (nếu cần)
        $categories = $categoryModel->index();

        // Trả về view danh sách
        $this->view('clientviews.product.index', [
            'products' => $products,
            'categories' => $categories,
            'title' => 'Tất cả sản phẩm - Vua Bóng Đá'
        ]);
    }

    // URL: /product/detail/{id} (Trang chi tiết sản phẩm)
    public function detail($id)
    {
        $productModel = $this->model('Product');
        $variantModel = $this->model('ProductVariant');

        // 1. Lấy thông tin sản phẩm (đã join bảng brand, category)
        $product = $productModel->getDetail($id);

        // Nếu không tìm thấy hoặc sản phẩm bị ẩn (status=0) -> Báo lỗi
        if (!$product) {
            $this->notFound("Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.");
            return;
        }

        // 2. Lấy danh sách biến thể (Size, Màu, Ảnh riêng)
        $variants = $variantModel->getByProductId($id);

        // 3. Lấy sản phẩm liên quan (Cùng danh mục)
        $relatedProducts = $productModel->getRelatedProducts($product['category_id'], $id);

        // 4. Trả về View chi tiết
        $this->view('clientviews.product.detail', [
            'product' => $product,
            'variants' => $variants,
            'relatedProducts' => $relatedProducts,
            'title' => $product['name'] . ' - Vua Bóng Đá'
        ]);
    }
}