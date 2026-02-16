@extends('layouts.client')

@section('title', $title ?? 'Sản phẩm yêu thích')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sản phẩm yêu thích</li>
        </ol>
    </nav>

    <h2 class="mb-4 text-danger"><i class="fas fa-heart me-2"></i>Sản phẩm yêu thích của bạn</h2>
    
    @if(is_array($products) && count($products) > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($products as $product)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative overflow-hidden p-2 text-center bg-light">
                        <a href="/product/detail/{{ $product['id'] }}">
                            <img src="/uploads/products/{{ $product['image'] ?? 'default.png' }}" class="card-img-top" style="height: 200px; object-fit: contain;">
                        </a>
                        <!-- Nút xóa khỏi yêu thích -->
                        <a href="/product/removeWishlist/{{ $product['id'] }}" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle shadow" data-bs-toggle="tooltip" title="Bỏ yêu thích">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <div class="card-body d-flex flex-column text-center">
                        <span class="text-success small fw-bold text-uppercase mb-1">{{ $product['category_name'] ?? 'Giày bóng đá' }}</span>
                        <h5 class="card-title fs-6 fw-semibold text-truncate mb-2">
                            <a href="/product/detail/{{ $product['id'] }}" class="text-dark text-decoration-none">{{ $product['name'] }}</a>
                        </h5>
                        <div class="mt-auto">
                            <span class="text-danger fw-bold fs-5">{{ number_format($product['price'], 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center bg-white p-5 rounded shadow-sm">
            <i class="far fa-heart fa-4x text-muted mb-3"></i>
            <h4 class="text-secondary fw-bold">Danh sách yêu thích trống!</h4>
            <p class="text-muted">Bạn chưa lưu sản phẩm nào vào danh sách yêu thích.</p>
            <a href="/product" class="btn btn-success mt-3 px-4 rounded-pill">Tiếp tục mua sắm</a>
        </div>
    @endif
</div>

<style>
    .product-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
@endsection