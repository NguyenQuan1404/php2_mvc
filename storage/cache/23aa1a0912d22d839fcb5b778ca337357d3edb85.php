

<?php $__env->startSection('title', 'Quản lý Sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Quản lý Sản phẩm</h4>
        <a href="/product/create" class="btn btn-light btn-sm fw-bold">
            <i class="bi bi-plus-circle"></i> Thêm Sản phẩm
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Hãng</th>
                        <th>Giá bán</th>
                        <th class="text-center">Kho</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center"><?php echo e($p['id']); ?></td>
                        <td class="text-center">
                            <?php if($p['image']): ?>
                                <img src="/uploads/products/<?php echo e($p['image']); ?>" class="img-thumb border">
                            <?php else: ?>
                                <span class="badge bg-secondary">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?php echo e($p['name']); ?></td>
                        <td><?php echo e($p['category_name']); ?></td>
                        <td><?php echo e($p['brand_name']); ?></td>
                        <td>
                            <span class="text-danger fw-bold"><?php echo e(number_format($p['price'])); ?>đ</span>
                            <?php if($p['sale_price'] > 0): ?>
                                <br><small class="text-muted"><del><?php echo e(number_format($p['sale_price'])); ?>đ</del></small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo e($p['quantity']); ?></td>
                        <td class="text-center">
                            <?php if($p['status']): ?>
                                <span class="badge bg-success">Hiện</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Ẩn</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="/product/edit/<?php echo e($p['id']); ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="/product/delete/<?php echo e($p['id']); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">Chưa có dữ liệu sản phẩm.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\php2lq\app\views/product/index.blade.php ENDPATH**/ ?>