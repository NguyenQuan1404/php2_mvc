@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="card col-md-8 mx-auto shadow">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Sửa Người dùng: {{ $user['fullname'] }}</h4>
    </div>
    <div class="card-body p-4">
        {{-- ACTION: /admin/user/update --}}
        <form action="/admin/user/update/{{ $user['id'] }}" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Họ tên</label>
                    <input type="text" class="form-control" name="fullname" value="{{ $user['fullname'] }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone" value="{{ $user['phone'] }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email (Không thể thay đổi)</label>
                <input type="email" class="form-control bg-light" value="{{ $user['email'] }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mật khẩu mới</label>
                <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu mới nếu muốn đổi...">
                <div class="form-text">Để trống nếu bạn không muốn đổi mật khẩu.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Địa chỉ</label>
                <input type="text" class="form-control" name="address" value="{{ $user['address'] }}">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Vai trò</label>
                <select class="form-select" name="role">
                    <option value="0" {{ $user['role'] == 0 ? 'selected' : '' }}>Khách hàng</option>
                    <option value="1" {{ $user['role'] == 1 ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Cập nhật
                </button>
                {{-- LINK: /admin/user --}}
                <a href="/admin/user" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection