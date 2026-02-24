@extends('layouts.client') {{-- Kế thừa layout chính --}}

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="/user/profile" class="list-group-item list-group-item-action">Thông tin tài khoản</a>
                <a href="/user/changePassword" class="list-group-item list-group-item-action active">Đổi mật khẩu</a>
                <a href="/myorder/index" class="list-group-item list-group-item-action">Lịch sử mua hàng</a>
                <a href="/auth/logout" class="list-group-item list-group-item-action text-danger">Đăng xuất</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    {{-- Thông báo --}}
                    @if(isset($msg))
                        <div class="alert alert-success">{{ $msg }}</div>
                    @endif
                    
                    @if(isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif

                    <form action="/user/updatePassword" method="POST">
                        {{-- @csrf --}}
                        
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu cũ</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                            <small class="text-muted">Tối thiểu 6 ký tự.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection