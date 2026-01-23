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
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning"><h4>Sửa Sản phẩm</h4></div>
        <div class="card-body">
            <form action="/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Loại đế</label>
                        <select class="form-select" name="category_id" required>
                            <?php foreach($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                    <?= $c['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <select class="form-select" name="brand_id">
                            <option value="">-- Chọn hãng --</option>
                            <?php foreach($brands as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= $b['id'] == $product['brand_id'] ? 'selected' : '' ?>>
                                    <?= $b['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá gốc</label>
                        <input type="number" class="form-control" name="price" value="<?= $product['price'] ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input type="number" class="form-control" name="sale_price" value="<?= $product['sale_price'] ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" class="form-control" name="quantity" value="<?= $product['quantity'] ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" class="form-control" name="image">
                    <?php if($product['image']): ?>
                        <div class="mt-2">
                            <img src="<?= $rootPath ?>/uploads/products/<?= $product['image'] ?>" height="100">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả ngắn</label>
                    <textarea class="form-control" name="short_description" rows="2"><?= htmlspecialchars($product['short_description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả chi tiết</label>
                    <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="status" value="1" <?= $product['status'] ? 'checked' : '' ?> id="statusCheck">
                    <label class="form-check-label" for="statusCheck">Hiển thị sản phẩm</label>
                </div>

                <button type="submit" class="btn btn-warning">Cập nhật</button>
                <a href="/product" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>