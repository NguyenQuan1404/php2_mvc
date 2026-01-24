@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="card col-md-8 mx-auto shadow">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">Thêm Người dùng mới</h4>
    </div>
    <div class="card-body p-4">
        <form action="/user/store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Họ tên</label>
                    <input type="text" class="form-control" name="fullname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Email (Tài khoản)</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Mật khẩu</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Địa chỉ</label>
                <input type="text" class="form-control" name="address">
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Vai trò</label>
                <select class="form-select" name="role">
                    <option value="0">Khách hàng (User)</option>
                    <option value="1">Quản trị viên (Admin)</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-info text-white">
                    <i class="bi bi-save"></i> Lưu User
                </button>
                <a href="/user" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection