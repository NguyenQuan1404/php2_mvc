<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Chỉnh sửa' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Chỉnh Sửa Loại Đế</h4>
                </div>
                <div class="card-body p-4">
                    <form action="/category/update/<?= $category['id'] ?>" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label for="tendanhmuc" class="form-label fw-bold">Tên Loại đế (TF, FG, IC...)</label>
                            <input type="text" class="form-control" id="tendanhmuc" name="tendanhmuc" 
                                   value="<?= htmlspecialchars($category['name']) ?>" required>
                        </div>


                        <div class="mb-4">
                            <label for="hinhanh" class="form-label fw-bold">Hình ảnh</label>
                            <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*" onchange="previewImage(this)">
                            
                            <div class="mt-3 text-center">
                                <p class="mb-1 text-muted small fw-bold">Ảnh hiện tại / Xem trước:</p>
                                <?php 
                                    $rootPath = dirname($_SERVER['SCRIPT_NAME']); 
                                    $rootPath = ($rootPath == '/' || $rootPath == '\\') ? '' : $rootPath;
                                ?>
                                <?php if (!empty($category['image'])): ?>
                                    <img id="preview_img" 
                                         src="<?= $rootPath ?>/uploads/categories/<?= $category['image'] ?>" 
                                         class="img-thumbnail" 
                                         style="max-height: 200px; max-width: 100%; object-fit: contain;"
                                         alt="Chưa có ảnh"
                                         onerror="this.onerror=null; this.src='/uploads/categories/<?= $category['image'] ?>';">
                                <?php else: ?>
                                    <img id="preview_img" 
                                         src="https://placehold.co/400x300?text=No+Image" 
                                         class="img-thumbnail"
                                         style="max-height: 200px; display: none;">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/category" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-warning fw-bold">
                                <i class="bi bi-save"></i> Cập nhật
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    var preview = document.getElementById('preview_img');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>