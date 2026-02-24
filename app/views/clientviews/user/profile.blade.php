@extends('layouts.client') 

@section('content') 
<div class="container my-5">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="/user/profile" class="list-group-item list-group-item-action active">Thông tin tài khoản</a>
                <a href="/user/changePassword" class="list-group-item list-group-item-action">Đổi mật khẩu</a>
                <a href="/myorder/index" class="list-group-item list-group-item-action">Lịch sử mua hàng</a>
                <a href="/auth/logout" class="list-group-item list-group-item-action text-danger">Đăng xuất</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Hồ sơ cá nhân</h5>
                </div>
                <div class="card-body">
                    {{-- Hiển thị thông báo thành công --}}
                    @if(isset($msg))
                        <div class="alert alert-success">{{ $msg }}</div>
                    @endif

                    {{-- Hiển thị thông báo lỗi --}}
                    @if(isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif

                    <form action="/user/updateProfile" method="POST">
                        {{-- Laravel cần CSRF token, nếu dùng custom MVC thì có thể bỏ @csrf --}}
                        {{-- @csrf --}} 
                        
                        <div class="mb-3">
                            <label class="form-label">Email (Tên đăng nhập)</label>
                            <input type="text" class="form-control" value="{{ $user['email'] }}" disabled readonly>
                            <small class="text-muted">Bạn không thể thay đổi địa chỉ email.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" value="{{ $user['fullname'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user['phone'] }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng mặc định</label>
                            <textarea name="address" class="form-control" rows="3">{{ $user['address'] }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection