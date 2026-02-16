@extends('layouts.client')

@section('title', $title ?? 'Sản phẩm')

@section('content')
<div class="container py-5">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Lọc Sản Phẩm (Bên trái) -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold border-bottom pb-3 mb-3"><i class="fas fa-filter text-success me-2"></i>Bộ lọc</h5>

                    <form action="/product" method="GET" id="filterForm">
                        <!-- Giữ lại keyword nếu đang tìm kiếm -->
                        @if(!empty($filters['keyword']))
                        <input type="hidden" name="keyword" value="{{ $filters['keyword'] }}">
                        @endif

                        <!-- Danh mục -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Danh mục</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="category" value="" id="cat_all" {{ empty($filters['category_id']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_all">Tất cả sản phẩm</label>
                            </div>
                            @foreach($categories as $cat)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="category" value="{{ $cat['id'] }}" id="cat_{{ $cat['id'] }}" {{ $filters['category_id'] == $cat['id'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_{{ $cat['id'] }}">{{ $cat['name'] }}</label>
                            </div>
                            @endforeach
                        </div>

                        <!-- Thương hiệu -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Thương hiệu</h6>
                            <select name="brand" class="form-select">
                                <option value="">Tất cả thương hiệu</option>
                                @if(!empty($brands))
                                @foreach($brands as $brand)
                                <option value="{{ $brand['id'] }}" {{ $filters['brand_id'] == $brand['id'] ? 'selected' : '' }}>
                                    {{ $brand['name'] }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Khoảng giá -->
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Khoảng giá</h6>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" name="min_price" value="{{ $filters['min_price'] }}" class="form-control form-control-sm" placeholder="Từ (VNĐ)">
                                <span>-</span>
                                <input type="number" name="max_price" value="{{ $filters['max_price'] }}" class="form-control form-control-sm" placeholder="Đến (VNĐ)">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mb-2 fw-semibold">Áp dụng lọc</button>
                        <a href="/product" class="btn btn-outline-secondary w-100 btn-sm">Xóa bộ lọc</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột Hiển thị Sản phẩm (Bên phải) -->
        <div class="col-lg-9">

            <!-- Thanh Sắp xếp Top -->
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm mb-4">
                <span class="text-muted">Tìm thấy <strong class="text-success">{{ is_array($products) ? count($products) : 0 }}</strong> sản phẩm</span>
                <div class="d-flex align-items-center gap-2">
                    <label class="text-nowrap mb-0 fw-semibold text-muted">Sắp xếp:</label>
                    <select name="sort" form="filterForm" onchange="document.getElementById('filterForm').submit()" class="form-select form-select-sm" style="width: auto;">
                        <option value="newest" {{ $filters['sort'] == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ $filters['sort'] == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="price_desc" {{ $filters['sort'] == 'price_desc' ? 'selected' : '' }}>Giá: Cao xuống Thấp</option>
                        <option value="name_asc" {{ $filters['sort'] == 'name_asc' ? 'selected' : '' }}>Tên: A-Z</option>
                    </select>
                </div>
            </div>

            <!-- Lưới Sản Phẩm -->
            @if(is_array($products) && count($products) > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative overflow-hidden p-2 text-center bg-light">
                            <a href="/product/detail/{{ $product['id'] }}">
                                <img src="/uploads/products/{{ $product['image'] ?? 'default.png' }}" class="card-img-top" style="height: 200px; object-fit: contain;" alt="{{ $product['name'] }}">
                            </a>
                            @if(isset($product['sale_price']) && $product['sale_price'] > 0)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">Giảm giá</span>
                            @endif
                            <!-- Nút Thêm vào Yêu thích -->
                            <a href="/product/addWishlist/{{ $product['id'] }}" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Thêm vào yêu thích">
                                <i class="far fa-heart text-danger"></i>
                            </a>
                            <a href="/product/addCompare/{{ $product['id'] }}" class="btn btn-outline-info btn-sm rounded-circle" data-bs-toggle="tooltip" title="So sánh (Tối đa 3)">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="text-success small fw-bold text-uppercase mb-1">{{ $product['category_name'] ?? 'Giày bóng đá' }}</span>
                            <h5 class="card-title fs-6 fw-semibold text-truncate mb-2">
                                <a href="/product/detail/{{ $product['id'] }}" class="text-dark text-decoration-none">{{ $product['name'] }}</a>
                            </h5>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="text-danger fw-bold fs-5">{{ number_format($product['price'], 0, ',', '.') }}đ</span>
                                <a href="/cart/add/{{ $product['id'] }}" class="btn btn-outline-success btn-sm rounded-circle" data-bs-toggle="tooltip" title="Thêm vào giỏ">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center bg-white p-5 rounded shadow-sm">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h4 class="text-secondary fw-bold">Không tìm thấy sản phẩm nào!</h4>
                <p class="text-muted">Vui lòng thử điều chỉnh lại bộ lọc hoặc từ khóa tìm kiếm.</p>
            </div>
            @endif

        </div>
    </div>
</div>

<!-- Thêm chút CSS để card có hiệu ứng hover mượt mà như các trang e-commerce -->
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection