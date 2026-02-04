@extends('layouts.client')

@section('title', 'Đăng ký tài khoản')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-success">ĐĂNG KÝ</h3>
                        <p class="text-muted small">Tạo tài khoản để mua sắm dễ dàng hơn</p>
                    </div>

                    @if(isset($error))
                        <div class="alert alert-danger small">{{ $error }}</div>
                    @endif

                    <!-- Action trỏ về handleRegister -->
                    <form action="/auth/handleRegister" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" value="{{ $fullname ?? '' }}" required placeholder="Ví dụ: Nguyễn Văn A">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $email ?? '' }}" required placeholder="email@example.com">
                        </div>
                        
                        <!-- Thêm số điện thoại cho khớp database User -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" required placeholder="Số điện thoại của bạn">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Nhập lại mật khẩu</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success fw-bold text-uppercase">Đăng Ký</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <span class="small text-muted">Đã có tài khoản?</span>
                        <a href="/auth/login" class="fw-bold text-success text-decoration-none small">Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection