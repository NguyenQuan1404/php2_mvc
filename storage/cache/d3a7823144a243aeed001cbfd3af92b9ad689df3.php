

<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>
<div class="card col-md-6 mx-auto shadow">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Sửa Thương hiệu: <?php echo e($brand['name']); ?></h4>
    </div>
    <div class="card-body p-4">
        <form action="/brand/update/<?php echo e($brand['id']); ?>" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Tên Thương hiệu</label>
                <input type="text" class="form-control" name="name" value="<?php echo e($brand['name']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea class="form-control" name="description" rows="3"><?php echo e($brand['description']); ?></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Cập nhật
                </button>
                <a href="/brand" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\php2lq\app\views/brand/edit.blade.php ENDPATH**/ ?>