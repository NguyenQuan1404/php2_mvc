<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card col-md-6 mx-auto shadow">
        <div class="card-header bg-warning"><h4>Sửa Thương hiệu</h4></div>
        <div class="card-body">
            <form action="/brand/update/<?= $brand['id'] ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên Thương hiệu</label>
                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($brand['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($brand['description']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-warning">Cập nhật</button>
                <a href="/brand" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>