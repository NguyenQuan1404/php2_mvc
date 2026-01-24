

<?php $__env->startSection('title', 'Trang chủ - Shop Giày Thể Thao'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Sidebar Categories -->
    <aside class="col-12 col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Danh mục</div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action active">Tất cả</a>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="#" class="list-group-item list-group-item-action">
                        <?php echo e($cate['name']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </aside>

    <!-- Products Grid -->
    <section class="col-12 col-lg-9">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 mb-0">Sản phẩm nổi bật</h1>
        </div>

        <div class="row g-3">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card h-100 shadow-sm">
                        <?php if($product['image']): ?>
                            <img src="/uploads/products/<?php echo e($product['image']); ?>" class="card-img-top" alt="<?php echo e($product['name']); ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <img src="https://placehold.co/600x400?text=No+Image" class="card-img-top" alt="No Image">
                        <?php endif; ?>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <h5 class="card-title mb-1 text-truncate" title="<?php echo e($product['name']); ?>"><?php echo e($product['name']); ?></h5>
                                <span class="badge text-bg-primary"><?php echo e($product['category_name'] ?? 'Giày'); ?></span>
                            </div>
                            <p class="card-text text-muted small mb-2 flex-grow-1">
                                <?php echo e($product['short_description'] ?? 'Mô tả đang cập nhật...'); ?>

                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="fw-semibold text-danger"><?php echo e(number_format($product['price'])); ?>đ</div>
                                <a href="#" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <div class="alert alert-warning">Chưa có sản phẩm nào.</div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\php2lq\app\views/home/index.blade.php ENDPATH**/ ?>