

<?php $__env->startSection('title', $title); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow-sm">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Danh sách Người dùng</h4>
        <a href="/user/create" class="btn btn-light btn-sm fw-bold">
            <i class="bi bi-person-plus-fill"></i> Thêm User
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center">ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th class="text-center">Vai trò</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($u['id']); ?></td>
                    <td class="fw-bold"><?php echo e($u['fullname']); ?></td>
                    <td><?php echo e($u['email']); ?></td>
                    <td><?php echo e($u['phone']); ?></td>
                    <td class="text-center">
                        <?php if($u['role'] == 1): ?>
                            <span class="badge bg-danger">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-primary">Khách hàng</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="/user/edit/<?php echo e($u['id']); ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="/user/delete/<?php echo e($u['id']); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-4">Chưa có người dùng nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\php2lq\app\views/user/index.blade.php ENDPATH**/ ?>