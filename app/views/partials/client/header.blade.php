<header class="bg-white shadow-sm sticky-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="fas fa-futbol fa-2x text-success me-2"></i>
                <div>
                    <h1 class="h4 fw-bold mb-0 text-success">Vua Bóng Đá</h1>
                    <span class="small text-muted">Đam mê bất tận</span>
                </div>
            </a>

            <!-- Toggle Button for Mobile -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Main Menu -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold">
                    <li class="nav-item">
                        <a class="nav-link px-3 active text-success" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 text-dark" href="/products">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 text-dark" href="/about">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 text-dark" href="/contact">Liên hệ</a>
                    </li>
                </ul>

                <!-- Right Actions -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Form -->
                    <form class="d-none d-lg-flex" role="search">
                        <div class="input-group rounded-pill bg-light border">
                            <input class="form-control border-0 bg-transparent py-2 ps-3" type="search" placeholder="Tìm kiếm..." aria-label="Search">
                            <button class="btn btn-link text-success pe-3" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Cart Icon -->
                    <a href="/cart" class="position-relative text-dark text-decoration-none me-2">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                            0
                        </span>
                    </a>

                    <!-- Account Actions (Login/Register or User Menu) -->
                    @if(isset($_SESSION['user']))
                        
                        {{-- Lấy role ra để dùng cho logic bên dưới --}}
                        @php $role = $_SESSION['user']['role'] ?? ''; @endphp

                        <!-- Dropdown User -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                    {{ strtoupper(substr($_SESSION['user']['fullname'], 0, 1)) }}
                                </div>
                                <span class="d-none d-sm-inline fw-semibold small">{{ $_SESSION['user']['fullname'] }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                                
                                {{-- CHỈ HIỆN TRONG MENU DROPDOWN --}}
                                @if($role == 1 || $role === 'admin')
                                    <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-tachometer-alt me-2 text-warning"></i>Trang quản trị</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                
                                <li><a class="dropdown-item" href="/profile"><i class="fas fa-user me-2 text-muted"></i>Hồ sơ cá nhân</a></li>
                                <li><a class="dropdown-item" href="/orders"><i class="fas fa-box-open me-2 text-muted"></i>Đơn hàng</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                            </ul>
                        </div>
                    @else
                        <div class="d-flex gap-2">
                            <a href="/auth/login" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold">Đăng nhập</a>
                            <a href="/auth/register" class="btn btn-success btn-sm rounded-pill px-3 fw-semibold">Đăng ký</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>
</header>