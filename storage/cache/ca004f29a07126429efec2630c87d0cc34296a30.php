

<?php $__env->startSection('title', 'Vua Bóng Đá - Trang chủ'); ?>

<?php $__env->startSection('content'); ?>

<!-- 1. Hero Banner Slider -->
<section class="mb-5">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner rounded-bottom shadow-sm">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div style="height: 500px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=1964&auto=format&fit=crop'); background-size: cover; background-position: center;">
                    <div class="container h-100 d-flex flex-column justify-content-center text-white">
                        <span class="badge bg-warning text-dark mb-2 px-3 py-2 fw-bold align-self-start">NEW COLLECTION</span>
                        <h1 class="display-3 fw-bolder mb-3 text-uppercase">Tốc độ & Kỹ thuật</h1>
                        <p class="fs-5 mb-4 col-md-6">Khám phá bộ sưu tập giày Mercurial mới nhất. Nhẹ hơn, nhanh hơn, kiểm soát bóng tốt hơn.</p>
                        <div>
                            <a href="#" class="btn btn-lg btn-success rounded-pill px-5 fw-bold shadow">Mua Ngay <i class="fas fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item">
                <div style="height: 500px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=1935&auto=format&fit=crop'); background-size: cover; background-position: center;">
                    <div class="container h-100 d-flex flex-column justify-content-center text-white">
                        <h1 class="display-3 fw-bolder mb-3 text-uppercase">Sân Cỏ Nhân Tạo</h1>
                        <p class="fs-5 mb-4 col-md-6">Đế TF chuyên dụng, bám sân cực tốt bất chấp trời mưa. Giảm chấn thương tối đa.</p>
                        <div>
                            <a href="#" class="btn btn-lg btn-light rounded-pill px-5 fw-bold shadow text-dark">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="carousel-item">
                <div style="height: 500px; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1543326727-25e6f5f9eb3c?q=80&w=2070&auto=format&fit=crop'); background-size: cover; background-position: center;">
                    <div class="container h-100 d-flex flex-column justify-content-center text-white">
                        <h1 class="display-3 fw-bolder mb-3 text-uppercase">Phụ Kiện Bóng Đá</h1>
                        <p class="fs-5 mb-4 col-md-6">Tất, găng tay, bọc ống đồng chính hãng. Nâng cao hiệu suất thi đấu của bạn.</p>
                        <div>
                            <a href="#" class="btn btn-lg btn-warning rounded-pill px-5 fw-bold shadow">Khám Phá</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- 2. Features / Policies -->
<section class="container mb-5">
    <div class="row g-4">
        <div class="col-md-3 col-6">
            <div class="p-4 bg-white rounded shadow-sm text-center h-100 border-bottom border-4 border-success">
                <i class="fas fa-shipping-fast fa-3x text-success mb-3"></i>
                <h6 class="fw-bold">Giao hàng siêu tốc</h6>
                <p class="small text-muted mb-0">Nhận hàng trong 1-2 ngày</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-4 bg-white rounded shadow-sm text-center h-100 border-bottom border-4 border-warning">
                <i class="fas fa-check-circle fa-3x text-warning mb-3"></i>
                <h6 class="fw-bold">Chính hãng 100%</h6>
                <p class="small text-muted mb-0">Đền gấp 10 nếu fake</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-4 bg-white rounded shadow-sm text-center h-100 border-bottom border-4 border-primary">
                <i class="fas fa-sync-alt fa-3x text-primary mb-3"></i>
                <h6 class="fw-bold">Đổi trả 30 ngày</h6>
                <p class="small text-muted mb-0">Nếu không vừa size</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-4 bg-white rounded shadow-sm text-center h-100 border-bottom border-4 border-danger">
                <i class="fas fa-headset fa-3x text-danger mb-3"></i>
                <h6 class="fw-bold">Hỗ trợ 24/7</h6>
                <p class="small text-muted mb-0">Tư vấn chọn giày</p>
            </div>
        </div>
    </div>
</section>

<!-- 3. Featured Categories -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-uppercase mb-1">Danh Mục Nổi Bật</h2>
            <div class="bg-success rounded" style="width: 60px; height: 4px;"></div>
        </div>
        <a href="#" class="btn btn-outline-dark rounded-pill px-4">Xem tất cả</a>
    </div>

    <div class="row g-3">
        <!-- Nếu có dữ liệu categories từ Controller -->
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-md-3">
                <a href="#" class="text-decoration-none">
                    <div class="card bg-dark text-white border-0 overflow-hidden shadow-sm category-card">
                         <?php if(!empty($cate['image'])): ?>
                            <img src="/uploads/categories/<?php echo e($cate['image']); ?>" class="card-img opacity-75" alt="<?php echo e($cate['name']); ?>" style="height: 250px; object-fit: cover;">
                        <?php else: ?>
                            <!-- Placeholder nếu không có ảnh -->
                             <div style="height: 250px; background-color: #333;" class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-shoe-prints fa-3x text-secondary"></i>
                             </div>
                        <?php endif; ?>
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4 gradient-overlay">
                            <h5 class="card-title fw-bold text-uppercase mb-0"><?php echo e($cate['name']); ?></h5>
                            <small class="text-warning"><i class="fas fa-arrow-right"></i> Mua ngay</small>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <!-- Nếu ít category quá, hiển thị thêm placeholder cho đẹp -->
        <?php if(count($categories) < 4): ?>
            <div class="col-6 col-md-3">
                <a href="#" class="text-decoration-none">
                    <div class="card bg-dark text-white border-0 overflow-hidden shadow-sm category-card">
                        <img src="https://images.unsplash.com/photo-1560272564-c83b66b1ad12?q=80&w=1949&auto=format&fit=crop" class="card-img opacity-75" style="height: 250px; object-fit: cover;">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4 gradient-overlay">
                            <h5 class="card-title fw-bold text-uppercase mb-0">Phụ Kiện</h5>
                            <small class="text-warning"><i class="fas fa-arrow-right"></i> Mua ngay</small>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- 4. Featured Products Grid -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-uppercase mb-1">Sản Phẩm Mới</h2>
            <div class="bg-success rounded" style="width: 60px; height: 4px;"></div>
        </div>
        
        <!-- Filter Tabs -->
        <ul class="nav nav-pills d-none d-md-flex">
            <li class="nav-item">
                <a class="nav-link active rounded-pill bg-success" aria-current="page" href="#">Tất cả</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">Nike</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">Adidas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">Mizuno</a>
            </li>
        </ul>
    </div>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card bg-white shadow-sm position-relative">
                    <!-- Sale Badge -->
                    <?php if($product['sale_price'] > 0 && $product['sale_price'] < $product['price']): ?>
                        <?php 
                            $percent = round((($product['price'] - $product['sale_price']) / $product['price']) * 100); 
                        ?>
                        <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 small fw-bold rounded shadow-sm z-1">
                            -<?php echo e($percent); ?>%
                        </span>
                    <?php endif; ?>

                    <!-- Wishlist Btn -->
                    <button class="btn btn-light rounded-circle shadow-sm position-absolute top-0 end-0 m-2 p-2 z-1 text-secondary hover-danger">
                        <i class="far fa-heart"></i>
                    </button>

                    <!-- Image -->
                    <div class="product-image-wrapper bg-light">
                        <a href="#">
                            <?php if($product['image']): ?>
                                <img src="/uploads/products/<?php echo e($product['image']); ?>" alt="<?php echo e($product['name']); ?>">
                            <?php else: ?>
                                <img src="https://placehold.co/600x600?text=No+Image" alt="No Image">
                            <?php endif; ?>
                        </a>
                    </div>

                    <!-- Content -->
                    <div class="card-body p-3 d-flex flex-column">
                        <small class="text-muted text-uppercase mb-1" style="font-size: 0.7rem;">
                            <?php echo e($product['category_name'] ?? 'Giày Bóng Đá'); ?> • <?php echo e($product['brand_name'] ?? 'Chính Hãng'); ?>

                        </small>
                        <h6 class="card-title fw-bold text-dark mb-1 text-truncate">
                            <a href="#" class="text-decoration-none text-dark stretched-link"><?php echo e($product['name']); ?></a>
                        </h6>
                        
                        <!-- Price -->
                        <div class="mt-auto pt-2">
                            <?php if($product['sale_price'] > 0): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-danger fs-5"><?php echo e(number_format($product['sale_price'])); ?>đ</span>
                                    <span class="text-muted text-decoration-line-through small"><?php echo e(number_format($product['price'])); ?>đ</span>
                                </div>
                            <?php else: ?>
                                <span class="fw-bold text-dark fs-5"><?php echo e(number_format($product['price'])); ?>đ</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Add to cart hover (Mobile will show always, Desktop on hover via CSS if needed, but here simple) -->
                        <a href="#" class="btn btn-outline-success w-100 mt-3 rounded-pill fw-bold btn-sm">
                            <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" class="mb-3 opacity-50">
                <p class="text-muted">Chưa có sản phẩm nào được bày bán.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item disabled"><a class="page-link rounded-circle mx-1" href="#"><i class="fas fa-chevron-left"></i></a></li>
                <li class="page-item active"><a class="page-link rounded-circle mx-1 bg-success border-success" href="#">1</a></li>
                <li class="page-item"><a class="page-link rounded-circle mx-1 text-success" href="#">2</a></li>
                <li class="page-item"><a class="page-link rounded-circle mx-1 text-success" href="#">3</a></li>
                <li class="page-item"><a class="page-link rounded-circle mx-1 text-success" href="#"><i class="fas fa-chevron-right"></i></a></li>
            </ul>
        </nav>
    </div>
</section>

<!-- 5. Newsletter -->
<section class="bg-dark text-white py-5 mb-0">
    <div class="container text-center">
        <h3 class="fw-bold text-uppercase mb-2">Đăng ký nhận tin</h3>
        <p class="text-white-50 mb-4">Nhận thông báo về hàng mới và mã giảm giá sớm nhất</p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form class="d-flex gap-2">
                    <input type="email" class="form-control rounded-pill px-4" placeholder="Nhập email của bạn...">
                    <button class="btn btn-success rounded-pill px-4 fw-bold">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .category-card { transition: transform 0.3s; cursor: pointer; }
    .category-card:hover { transform: translateY(-5px); }
    .gradient-overlay { background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); }
    .hover-danger:hover { color: #dc3545 !important; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\php2lq\app\views/home/index.blade.php ENDPATH**/ ?>