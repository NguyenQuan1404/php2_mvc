<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị Shop Giày')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .img-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
        /* Active menu styling */
        .nav-link.active { font-weight: bold; color: #fff !important; }
    </style>
</head>
<body class="bg-light">

@php
    // Lấy URI hiện tại để xử lý active menu đơn giản
    $currentUri = $_SERVER['REQUEST_URI'] ?? '/';
@endphp

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">Shop Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ strpos($currentUri, '/product') !== false ? 'active' : '' }}" href="/product">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ strpos($currentUri, '/category') !== false ? 'active' : '' }}" href="/category">Danh mục</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ strpos($currentUri, '/brand') !== false ? 'active' : '' }}" href="/brand">Thương hiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ strpos($currentUri, '/user') !== false ? 'active' : '' }}" href="/user">Người dùng</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    {{-- Hiển thị thông báo lỗi hoặc thành công từ Session (nếu bạn có set $_SESSION['success']) --}}
    @if(isset($_SESSION['success']))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $_SESSION['success'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @php unset($_SESSION['success']); @endphp
    @endif

    @if(isset($_SESSION['error']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @php unset($_SESSION['error']); @endphp
    @endif

    {{-- Nội dung chính sẽ được render ở đây --}}
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete() {
        return confirm('Bạn có chắc chắn muốn xóa không?');
    }
    
    // Preview image script
    function previewImage(input) {
        var preview = document.getElementById('preview_img');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if(preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@yield('scripts')
</body>
</html>