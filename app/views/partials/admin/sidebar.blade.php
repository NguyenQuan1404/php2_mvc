<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark admin-sidebar-inner" style="width: 260px; height: 100vh;">
    <a href="/dashboard" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none px-2 brand-logo">
        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
            <i class="fa-solid fa-shoe-prints fa-lg"></i>
        </div>
        <span class="fs-4 fw-bold tracking-tight">Admin<span class="text-primary">Panel</span></span>
    </a>
    <hr class="border-secondary opacity-50 my-4">
    
    <ul class="nav nav-pills flex-column mb-auto">
        @php
            $currentUri = $_SERVER['REQUEST_URI'] ?? '';
        @endphp

        <li class="nav-item mb-2">
            <a href="/dashboard" class="nav-link text-white {{ (strpos($currentUri, '/dashboard') !== false || $currentUri == '/admin') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high me-3 icon-width"></i>
                Tổng quan
            </a>
        </li>
        
        <li class="nav-header text-uppercase text-white-50 fw-bold fs-7 mt-3 mb-2 px-3">Quản lý Kho</li>

        <li class="nav-item mb-1">
            <a href="/category" class="nav-link text-white {{ strpos($currentUri, '/category') !== false ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group me-3 icon-width"></i>
                Danh mục (Đế giày)
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="/brand" class="nav-link text-white {{ strpos($currentUri, '/brand') !== false ? 'active' : '' }}">
                <i class="fa-solid fa-copyright me-3 icon-width"></i>
                Thương hiệu
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="/product" class="nav-link text-white {{ strpos($currentUri, '/product') !== false ? 'active' : '' }}">
                <i class="fa-solid fa-box-open me-3 icon-width"></i>
                Sản phẩm
            </a>
        </li>

        <li class="nav-header text-uppercase text-white-50 fw-bold fs-7 mt-3 mb-2 px-3">Hệ thống</li>

        <li class="nav-item mb-1">
            <a href="/user" class="nav-link text-white {{ strpos($currentUri, '/user') !== false ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear me-3 icon-width"></i>
                Người dùng (User)
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="#" class="nav-link text-white">
                <i class="fa-solid fa-file-invoice-dollar me-3 icon-width"></i>
                Đơn hàng <span class="badge bg-danger ms-auto rounded-pill" style="font-size: 0.7em;">Soon</span>
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
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
        </ul>
    </div>
</div>