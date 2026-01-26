@extends('layouts.client')

@section('title', 'Đăng nhập')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-success">ĐĂNG NHẬP</h3>
                        <p class="text-muted small">Chào mừng bạn đến với Vua Bóng Đá</p>
                    </div>

                    @if(isset($_GET['msg']) && $_GET['msg'] == 'registered')
                        <div class="alert alert-success small">Đăng ký thành công! Hãy đăng nhập.</div>
                    @endif

                    @if(isset($error))
                        <div class="alert alert-danger small">{{ $error }}</div>
                    @endif

                    <form action="/auth/login" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $email ?? '' }}" required placeholder="Nhập email...">
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-bold small">Mật khẩu</label>
                                <a href="/auth/forgot-password" class="small text-decoration-none text-success">Quên mật khẩu?</a>
                            </div>
                            <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu...">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success fw-bold text-uppercase">Đăng nhập</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <span class="small text-muted">Chưa có tài khoản?</span>
                        <a href="/auth/register" class="fw-bold text-success text-decoration-none small">Đăng ký ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection