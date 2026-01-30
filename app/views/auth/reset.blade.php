@extends('layouts.client')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Xác thực OTP & Đổi Mật Khẩu</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Action trỏ về AuthController -> hàm updatePassword -->
                    <form action="/auth/updatePassword" method="POST">
                        <!-- Email được truyền từ controller, ẩn đi -->
                        <input type="hidden" name="email" value="<?= $email ?>">
                        
                        <!-- Ô nhập OTP mới -->
                        <div class="mb-3">
                            <label for="otp" class="form-label fw-bold">Mã OTP (6 số)</label>
                            <input type="text" class="form-control" id="otp" name="otp" required placeholder="Nhập mã OTP trong email..." maxlength="6" style="letter-spacing: 2px; font-size: 1.1rem;">
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Nhập mật khẩu mới">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu mới">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Xác nhận & Đổi Mật Khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection