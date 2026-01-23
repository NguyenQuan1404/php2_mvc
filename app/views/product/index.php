<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php 
    $rootPath = dirname($_SERVER['SCRIPT_NAME']); 
    $rootPath = ($rootPath == '/' || $rootPath == '\\') ? '' : $rootPath;
?>
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h4 class="mb-0">Quản lý Sản phẩm</h4>
            <a href="/product/create" class="btn btn-light btn-sm fw-bold">Thêm Sản phẩm</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Hãng</th>
                            <th>Giá bán</th>
                            <th>Kho</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td>
                                <?php if($p['image']): ?>
                                    <img src="<?= $rootPath ?>/uploads/products/<?= $p['image'] ?>" width="60">
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= $p['category_name'] ?></td>
                            <td><?= $p['brand_name'] ?></td>
                            <td>
                                <span class="text-danger fw-bold"><?= number_format($p['price']) ?>đ</span>
                                <?php if($p['sale_price'] > 0): ?>
                                    <br><small class="text-muted"><del><?= number_format($p['sale_price']) ?>đ</del></small>
                                <?php endif; ?>
                            </td>
                            <td><?= $p['quantity'] ?></td>
                            <td><?= $p['status'] ? '<span class="badge bg-success">Hiện</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?></td>
                            <td>
                                <a href="/product/edit/<?= $p['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="/product/delete/<?= $p['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Xóa?');">
                                    <button class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>