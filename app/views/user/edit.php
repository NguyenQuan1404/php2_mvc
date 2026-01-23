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
        <div class="card-header bg-warning"><h4>Sửa Người dùng</h4></div>
        <div class="card-body">
            <form action="/user/update/<?= $user['id'] ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email (Không thể thay đổi)</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu mới (Để trống nếu không đổi)</label>
                    <input type="password" class="form-control" name="password" placeholder="Nhập pass mới nếu muốn đổi...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($user['address']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Vai trò</label>
                    <select class="form-select" name="role">
                        <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Khách hàng</option>
                        <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning">Cập nhật</button>
                <a href="/user" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>