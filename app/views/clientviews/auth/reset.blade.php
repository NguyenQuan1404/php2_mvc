@extends('layouts.client')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white text-center">
                    <h5 class="mb-0">Xác thực OTP & Đổi Mật Khẩu</h5>
                </div>
                <div class="card-body p-4">
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

                    <form action="/auth/updatePassword" method="POST">
                        <input type="hidden" name="email" value="{{ $email }}">
                        
                        <div class="mb-3">
                            <label for="otp" class="form-label fw-bold">Mã OTP (6 số)</label>
                            <input type="text" class="form-control text-center letter-spacing-2" id="otp" name="otp" required placeholder="xxxxxx" maxlength="6" style="letter-spacing: 3px; font-size: 1.2rem;">
                            <div class="form-text text-center">Kiểm tra email của bạn để lấy mã.</div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Nhập mật khẩu mới">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label fw-bold">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu mới">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Xác nhận & Đổi Mật Khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection