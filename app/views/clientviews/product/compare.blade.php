@extends('layouts.client')

@section('title', $title ?? 'So sánh sản phẩm')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-success">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">So sánh sản phẩm</li>
        </ol>
    </nav>

    <h2 class="mb-4 text-info"><i class="fas fa-exchange-alt me-2"></i>So sánh sản phẩm</h2>
    
    @if(is_array($products) && count($products) > 0)
        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20%;" class="text-start align-middle">Đặc điểm</th>
                        @foreach($products as $product)
                            <th style="width: {{ 80 / count($products) }}%;">
                                <div class="position-relative">
                                    <a href="/product/removeCompare/{{ $product['id'] }}" class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle" data-bs-toggle="tooltip" title="Xóa khỏi so sánh">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <img src="/uploads/products/{{ $product['image'] ?? 'default.png' }}" class="img-fluid mb-2" style="height: 150px; object-fit: contain;">
                                    <h5 class="fs-6 fw-bold"><a href="/product/detail/{{ $product['id'] }}" class="text-dark text-decoration-none">{{ $product['name'] }}</a></h5>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-start fw-semibold text-muted">Giá bán</td>
                        @foreach($products as $product)
                            <td><span class="text-danger fw-bold fs-5">{{ number_format($product['price'], 0, ',', '.') }}đ</span></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="text-start fw-semibold text-muted">Thương hiệu</td>
                        @foreach($products as $product)
                            <td><span class="badge bg-secondary">{{ $product['brand_name'] ?? 'Đang cập nhật' }}</span></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="text-start fw-semibold text-muted">Danh mục</td>
                        @foreach($products as $product)
                            <td>{{ $product['category_name'] ?? 'Đang cập nhật' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="text-start fw-semibold text-muted">Hành động</td>
                        @foreach($products as $product)
                            <td>
                                <a href="/cart/add/{{ $product['id'] }}" class="btn btn-success btn-sm w-100 mb-2"><i class="fas fa-shopping-cart me-1"></i>Thêm vào giỏ</a>
                                <a href="/product/addWishlist/{{ $product['id'] }}" class="btn btn-outline-danger btn-sm w-100"><i class="far fa-heart me-1"></i>Yêu thích</a>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center bg-white p-5 rounded shadow-sm">
            <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
            <h4 class="text-secondary fw-bold">Chưa có sản phẩm nào để so sánh!</h4>
            <p class="text-muted">Vui lòng quay lại danh sách sản phẩm và chọn tối đa 3 sản phẩm để so sánh tính năng.</p>
            <a href="/product" class="btn btn-info text-white mt-3 px-4 rounded-pill">Chọn sản phẩm ngay</a>
        </div>
    @endif
</div>
@endsection