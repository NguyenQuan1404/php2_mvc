<?php
namespace App\Controllers\Admin;

use Controller;
class CouponController extends Controller
{
    public function index()
    {
        $coupons = $this->model('Coupon')->getAll();
        $this->view('/adminviews/coupon/index', [
            'coupons' => $coupons,
            'title' => 'Quản lý Mã giảm giá'
        ]);
    }

    public function create()
    {
        $this->view('coupon/create', [
            'title' => 'Thêm Mã giảm giá'
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $couponModel = $this->model('Coupon');
            $code = strtoupper($_POST['code']);

            // Kiểm tra trùng mã
            if ($couponModel->checkCodeExists($code)) {
                // Đơn giản hóa: Nếu trùng thì quay lại trang create (Thực tế nên có thông báo lỗi)
                echo "<script>alert('Mã giảm giá này đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $data = [
                'code' => $code,
                'type' => $_POST['type'],
                'value' => $_POST['value'],
                'min_order_value' => $_POST['min_order_value'] ?? 0,
                'quantity' => $_POST['quantity'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            if ($couponModel->create($data)) {
                header('Location: /coupon');
            } else {
                echo "Có lỗi xảy ra!";
            }
        }
    }

    public function edit($id)
    {
        $couponModel = $this->model('Coupon');
        // Sử dụng hàm show kế thừa từ Model cha (nếu có) hoặc viết query select * from coupons where id = $id
        // Giả sử Model cha có hàm show($id) hoặc find($id)
        // Nếu Model cha chưa có, ta dùng query trực tiếp ở đây hoặc thêm vào model
        
        // Để chắc chắn, ta gọi phương thức show từ Model (thường các framework MVC cơ bản đều có)
        // Nếu lỗi, bạn hãy thêm function show($id) vào Model Coupon nhé.
        $coupon = $couponModel->show($id);

        if (!$coupon) {
            header('Location: /coupon');
            exit;
        }

        $this->view('coupon/edit', [
            'coupon' => $coupon,
            'title' => 'Sửa Mã giảm giá'
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $couponModel = $this->model('Coupon');
            $code = strtoupper($_POST['code']);

            // Kiểm tra trùng mã (trừ chính ID đang sửa)
            if ($couponModel->checkCodeExists($code, $id)) {
                echo "<script>alert('Mã giảm giá này đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $data = [
                'code' => $code,
                'type' => $_POST['type'],
                'value' => $_POST['value'],
                'min_order_value' => $_POST['min_order_value'] ?? 0,
                'quantity' => $_POST['quantity'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'status' => isset($_POST['status']) ? 1 : 0
            ];

            $couponModel->update($id, $data);
            header('Location: /coupon');
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model('Coupon')->delete($id);
            header('Location: /coupon');
        }
    }
}