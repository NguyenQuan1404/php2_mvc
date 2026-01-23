<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .img-thumbnail-custom {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">

<?php 
    $rootPath = dirname($_SERVER['SCRIPT_NAME']); 
    $rootPath = ($rootPath == '/' || $rootPath == '\\') ? '' : $rootPath;
?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i><?= $title ?></h4>
            <a href="/category/create" class="btn btn-light btn-sm fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Thêm mới
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center" width="5%">ID</th>
                            <th scope="col">Tên Loại đế (Category)</th>

                            <th scope="col" class="text-center">Hình ảnh</th>
                            <th scope="col" class="text-center" width="20%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($category)): ?>
                            <?php foreach ($category as $item): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $item['id'] ?></td>
                                    <td class="fw-semibold text-primary"><?= htmlspecialchars($item['name']) ?></td>
                                    
                                    <td class="text-center">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?= $rootPath ?>/uploads/categories/<?= $item['image'] ?>" 
                                                 class="img-thumbnail-custom border" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>"
                                                 onerror="this.onerror=null; this.src='/uploads/categories/<?= $item['image'] ?>';">
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="/category/edit/<?= $item['id'] ?>" class="btn btn-warning btn-sm text-dark">
                                                <i class="bi bi-pencil-square"></i> Sửa
                                            </a>
                                            
                                            <form action="/category/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Cảnh báo: Bạn có chắc chắn muốn xóa không?');">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>