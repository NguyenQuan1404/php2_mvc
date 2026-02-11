@extends('layouts.client')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0">Quên Mật Khẩu</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted text-center mb-4">Nhập email đã đăng ký để nhận mã OTP đặt lại mật khẩu.</p>
                    
                    @if (isset($_SESSION['error']))
                        <div class="alert alert-danger small">
                            {{ $_SESSION['error'] }}
                            @php unset($_SESSION['error']); @endphp
                        </div>
                    @endif

                    @if (isset($_SESSION['success']))
                        <div class="alert alert-success small">
                            {{ $_SESSION['success'] }}
                            @php unset($_SESSION['success']); @endphp
                        </div>
                    @endif

                    <form action="/auth/handleForgotPassword" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email đăng ký</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Gửi Mã OTP</button>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="/auth/login" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left"></i> Quay lại Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection