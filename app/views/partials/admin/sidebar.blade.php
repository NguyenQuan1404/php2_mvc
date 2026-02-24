<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark admin-sidebar-inner" style="width: 260px; min-height: 100vh;">
    <a href="/admin/dashboard" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none px-2 brand-logo">
        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
            <i class="fas fa-shoe-prints fa-lg"></i>
        </div>
        <span class="fs-4 fw-bold tracking-tight">Admin<span class="text-primary">Panel</span></span>
    </a>
    <hr class="border-secondary opacity-50 my-4">
    
    <ul class="nav nav-pills flex-column mb-auto">
        @php
            $currentUri = $_SERVER['REQUEST_URI'] ?? '';
            // Helper check active đơn giản
            $isActive = function($path) use ($currentUri) {
                return strpos($currentUri, $path) !== false ? 'active' : '';
            };
        @endphp

        <li class="nav-item mb-2">
            <a href="/admin/dashboard" class="nav-link text-white {{ ($isActive('/admin/dashboard') || $currentUri == '/admin') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-3 icon-width"></i>
                Tổng quan
            </a>
        </li>
        
        <li class="nav-header text-uppercase text-muted fs-7 fw-bold mt-4 mb-2 ps-2">Quản lý sản phẩm</li>

        <li class="nav-item mb-1">
            <a href="/admin/category" class="nav-link text-white {{ $isActive('/admin/category') }}">
                <i class="fas fa-layer-group me-3 icon-width"></i>
                Danh mục (Category)
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="/admin/brand" class="nav-link text-white {{ $isActive('/admin/brand') }}">
                <i class="fas fa-copyright me-3 icon-width"></i>
                Thương hiệu (Brand)
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="/admin/product" class="nav-link text-white {{ $isActive('/admin/product') }}">
                <i class="fas fa-box-open me-3 icon-width"></i>
                Sản phẩm (Products)
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="/admin/coupon" class="nav-link text-white {{ $isActive('/admin/coupon') }}">
                <i class="fas fa-ticket-alt me-3 icon-width"></i>
                Mã giảm giá (Coupon)
            </a>
        </li>

        <li class="nav-header text-uppercase text-muted fs-7 fw-bold mt-4 mb-2 ps-2">Hệ thống</li>

        <li class="nav-item mb-1">
            <a href="/admin/user" class="nav-link text-white {{ $isActive('/admin/user') }}">
                <i class="fas fa-users-cog me-3 icon-width"></i>
                Người dùng (User)
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="/admin/order/index" class="nav-link text-white">
                <i class="fas fa-file-invoice-dollar me-3 icon-width"></i>
                Đơn hàng
            </a>
        </li>
    </ul>
    
    <hr class="border-secondary opacity-50">
    <div class="dropdown px-2">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>Admin</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="/">Về trang chủ Shop</a></li>
            <li><a class="dropdown-item" href="#">Cài đặt</a></li>
            <li><a class="dropdown-item" href="#">Hồ sơ</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="/auth/logout">Đăng xuất</a></li>
        </ul>
    </div>
</div>