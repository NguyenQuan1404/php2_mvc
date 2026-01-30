@extends('layouts.admin')

@section('title', 'Thêm Mã giảm giá')

@section('content')
<div class="card shadow col-md-8 mx-auto">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Tạo Mã Giảm Giá Mới</h4>
    </div>
    <div class="card-body">
        <form action="/coupon/store" method="POST">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã Code (VD: SALE50)</label>
                    <input type="text" class="form-control text-uppercase" name="code" required placeholder="Nhập mã...">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Số lượng phát hành</label>
                    <input type="number" class="form-control" name="quantity" required min="1" value="100">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Loại giảm giá</label>
                    <select class="form-select" name="type" id="discountType">
                        <option value="fixed">Trừ tiền trực tiếp (VND)</option>
                        <option value="percent">Trừ theo phần trăm (%)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Giá trị giảm</label>
                    <input type="number" class="form-control" name="value" required min="0">
                    <small class="text-muted" id="valueHint">Nhập số tiền VNĐ</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Đơn hàng tối thiểu</label>
                    <input type="number" class="form-control" name="min_order_value" value="0" min="0">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input type="date" class="form-control" name="start_date" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input type="date" class="form-control" name="end_date" required>
                </div>
            </div>

            <div class="mb-4 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" checked>
                <label class="form-check-label fw-bold" for="statusSwitch">Kích hoạt ngay lập tức</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Lưu Mã</button>
                <a href="/coupon" class="btn btn-secondary">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Script nhỏ để đổi gợi ý khi chọn loại giảm giá
    document.getElementById('discountType').addEventListener('change', function() {
        const hint = document.getElementById('valueHint');
        if(this.value === 'percent') {
            hint.textContent = 'Nhập số % (VD: 10, 20)';
        } else {
            hint.textContent = 'Nhập số tiền VNĐ';
        }
    });
</script>
@endsection