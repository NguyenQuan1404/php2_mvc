@extends('layouts.admin')

@section('title', 'Sửa Mã giảm giá')

@section('content')
<div class="card shadow col-md-8 mx-auto">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">Cập nhật Mã: {{ $coupon['code'] }}</h4>
    </div>
    <div class="card-body">
        <form action="/coupon/update/{{ $coupon['id'] }}" method="POST">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã Code</label>
                    <input type="text" class="form-control text-uppercase" name="code" value="{{ $coupon['code'] }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Số lượng phát hành</label>
                    <input type="number" class="form-control" name="quantity" value="{{ $coupon['quantity'] }}" required min="0">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Loại giảm giá</label>
                    <select class="form-select" name="type" id="discountType">
                        <option value="fixed" {{ $coupon['type'] == 'fixed' ? 'selected' : '' }}>Trừ tiền trực tiếp (VND)</option>
                        <option value="percent" {{ $coupon['type'] == 'percent' ? 'selected' : '' }}>Trừ theo phần trăm (%)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Giá trị giảm</label>
                    <input type="number" class="form-control" name="value" value="{{ (int)$coupon['value'] }}" required min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Đơn hàng tối thiểu</label>
                    <input type="number" class="form-control" name="min_order_value" value="{{ (int)$coupon['min_order_value'] }}" min="0">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                    <input type="date" class="form-control" name="start_date" value="{{ $coupon['start_date'] }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày kết thúc</label>
                    <input type="date" class="form-control" name="end_date" value="{{ $coupon['end_date'] }}" required>
                </div>
            </div>

            <div class="mb-4 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" {{ $coupon['status'] == 1 ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="statusSwitch">Kích hoạt</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Cập nhật</button>
                <a href="/coupon" class="btn btn-secondary">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection