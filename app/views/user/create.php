<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card col-md-8 mx-auto shadow">
        <div class="card-header bg-info text-white"><h4>Thêm Người dùng</h4></div>
        <div class="card-body">
            <form action="/user/store" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" class="form-control" name="fullname" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email (Tài khoản đăng nhập)</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address">
                </div>
                <div class="mb-3">
                    <label class="form-label">Vai trò</label>
                    <select class="form-select" name="role">
                        <option value="0">Khách hàng (User)</option>
                        <option value="1">Quản trị viên (Admin)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-info text-white">Lưu User</button>
                <a href="/user" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>