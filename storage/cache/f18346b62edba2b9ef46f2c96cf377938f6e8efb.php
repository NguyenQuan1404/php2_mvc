

<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i><?php echo e($title); ?></h4>
        <a href="/category/create" class="btn btn-light btn-sm fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Thêm mới
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">ID</th>
                        <th>Tên Loại đế (Category)</th>
                        <th class="text-center">Hình ảnh</th>
                        <th class="text-center" width="20%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center fw-bold"><?php echo e($item['id']); ?></td>
                            <td class="fw-semibold text-primary"><?php echo e($item['name']); ?></td>
                            <td class="text-center">
                                <?php if(!empty($item['image'])): ?>
                                    <img src="/uploads/categories/<?php echo e($item['image']); ?>" 
                                         class="img-thumb border" 
                                         alt="<?php echo e($item['name']); ?>">
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="/category/edit/<?php echo e($item['id']); ?>" class="btn btn-warning btn-sm text-dark">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <form action="/category/delete/<?php echo e($item['id']); ?>" method="POST" onsubmit="return confirmDelete();">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\php2lq\app\views/category/index.blade.php ENDPATH**/ ?>