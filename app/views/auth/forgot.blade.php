@extends('layouts.client')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Quên Mật Khẩu</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Nhập email của bạn để nhận liên kết đặt lại mật khẩu.</p>
                    
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

                    <!-- Action trỏ về AuthController -> hàm sendReset -->
                    <form action="/auth/sendReset" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email đăng ký</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Gửi Link Reset</button>
                        <div class="mt-3 text-center">
                            <!-- Link quay lại AuthController -> hàm login -->
                            <a href="/auth/login" class="text-decoration-none">Quay lại Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection