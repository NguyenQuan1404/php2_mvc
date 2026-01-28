@extends('layouts.client')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5 text-center">
                    <i class="fas fa-lock fa-3x text-warning mb-3"></i>
                    <h4 class="fw-bold text-dark">Quên Mật Khẩu?</h4>
                    <p class="text-muted small mb-4">Nhập email của bạn để lấy lại mật khẩu.</p>

                    @if(isset($success))
                        <div class="alert alert-success small">{{ $success }}</div>
                    @endif

                    @if(isset($error))
                        <div class="alert alert-danger small">{{ $error }}</div>
                    @endif

                    <!-- Action trỏ về handleForgotPassword -->
                    <form action="/auth/handleForgotPassword" method="POST">
                        <div class="mb-3 text-start">
                            <label class="form-label fw-bold small">Email đăng ký</label>
                            <input type="email" name="email" class="form-control" required placeholder="nhapemail@example.com">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white">Gửi Yêu Cầu</button>
                            <a href="/auth/login" class="btn btn-light text-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection