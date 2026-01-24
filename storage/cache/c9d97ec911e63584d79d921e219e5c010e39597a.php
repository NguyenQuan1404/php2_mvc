

<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Danh sách Thương hiệu</h4>
        <a href="/brand/create" class="btn btn-success btn-sm">
            <i class="bi bi-plus-circle"></i> Thêm mới
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-secondary">
                <tr>
                    <th class="text-center" width="5%">ID</th>
                    <th>Tên Thương hiệu</th>
                    <th>Mô tả</th>
                    <th class="text-center" width="20%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($item['id']); ?></td>
                    <td class="fw-bold"><?php echo e($item['name']); ?></td>
                    <td><?php echo e($item['description']); ?></td>
                    <td class="text-center">
                        <a href="/brand/edit/<?php echo e($item['id']); ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="/brand/delete/<?php echo e($item['id']); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center py-4">Chưa có thương hiệu nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\php2lq\app\views/brand/index.blade.php ENDPATH**/ ?>