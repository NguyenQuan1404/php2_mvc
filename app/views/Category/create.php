<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow col-md-6 mx-auto">
        <div class="card-header bg-success text-white"><h4>Thêm Danh Mục Mới</h4></div>
        <div class="card-body p-4">
            <form action="/category/store" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Danh mục</label>
          
                    <input type="text" class="form-control" name="tendanhmuc" placeholder="Ví dụ: Varpo 16,..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Hình ảnh</label>
                    <input type="file" class="form-control" name="hinhanh">
                </div>
                <button type="submit" class="btn btn-success">Lưu Danh mục</button>
                <a href="/category" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>