<?php
namespace App\Controllers\Admin;

use Controller;

class CouponController extends Controller
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

        // Gọi Model và dùng hàm index() cho đồng bộ
        $coupons = $this->model('Coupon')->index();
        
        $this->view('adminviews.coupon.index', [
            'coupons' => $coupons,
            'title' => 'Quản lý Mã giảm giá'
        ]);
    }

    public function create()
    {
        $this->view('adminviews.coupon.create', [
            'title' => 'Thêm Mã giảm giá'
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $couponModel = $this->model('Coupon');
            $code = strtoupper($_POST['code'] ?? '');

            // Kiểm tra trùng mã
            if ($couponModel->checkCodeExists($code)) {
                echo "<script>alert('Mã giảm giá này đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $data = [
                'code' => $code,
                'type' => $_POST['type'] ?? 'fixed',
                'value' => $_POST['value'] ?? 0,
                'min_order_value' => $_POST['min_order_value'] ?? 0,
                'quantity' => $_POST['quantity'] ?? 0,
                'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
                'end_date' => $_POST['end_date'] ?? date('Y-m-d'),
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            $couponModel->create($data);
            
            // Chuyển hướng về /admin/coupon
            header('Location: /admin/coupon');
            exit;
        }
    }

    public function edit($id)
    {
        $couponModel = $this->model('Coupon');
        $coupon = $couponModel->show($id); // Đảm bảo Model có hàm show

        if (!$coupon) {
            header('Location: /admin/coupon');
            exit;
        }

        $this->view('adminviews.coupon.edit', [
            'coupon' => $coupon,
            'title' => 'Sửa Mã giảm giá'
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $couponModel = $this->model('Coupon');
            $code = strtoupper($_POST['code'] ?? '');

            // Kiểm tra trùng mã (trừ chính ID đang sửa)
            if ($couponModel->checkCodeExists($code, $id)) {
                echo "<script>alert('Mã giảm giá này đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $data = [
                'code' => $code,
                'type' => $_POST['type'] ?? 'fixed',
                'value' => $_POST['value'] ?? 0,
                'min_order_value' => $_POST['min_order_value'] ?? 0,
                'quantity' => $_POST['quantity'] ?? 0,
                'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
                'end_date' => $_POST['end_date'] ?? date('Y-m-d'),
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            $couponModel->update($id, $data);
            
            // Chuyển hướng về /admin/coupon
            header('Location: /admin/coupon');
            exit;
        }
    }

    public function delete($id)
    {
        $this->model('Coupon')->delete($id);
        
        // Chuyển hướng về /admin/coupon
        header('Location: /admin/coupon');
        exit;
    }
}