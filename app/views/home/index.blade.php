@extends('layouts.client')

@section('title', 'Trang chủ - Shop Giày Thể Thao')

@section('content')
<div class="row">
    <!-- Sidebar Categories -->
    <aside class="col-12 col-lg-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Danh mục</div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action active">Tất cả</a>
                @foreach($categories as $cate)
                    <a href="#" class="list-group-item list-group-item-action">
                        {{ $cate['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </aside>

    <!-- Products Grid -->
    <section class="col-12 col-lg-9">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 mb-0">Sản phẩm nổi bật</h1>
        </div>

        <div class="row g-3">
            @forelse($products as $product)
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card h-100 shadow-sm">
                        @if($product['image'])
                            <img src="/uploads/products/{{ $product['image'] }}" class="card-img-top" alt="{{ $product['name'] }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://placehold.co/600x400?text=No+Image" class="card-img-top" alt="No Image">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <h5 class="card-title mb-1 text-truncate" title="{{ $product['name'] }}">{{ $product['name'] }}</h5>
                                <span class="badge text-bg-primary">{{ $product['category_name'] ?? 'Giày' }}</span>
                            </div>
                            <p class="card-text text-muted small mb-2 flex-grow-1">
                                {{ $product['short_description'] ?? 'Mô tả đang cập nhật...' }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="fw-semibold text-danger">{{ number_format($product['price']) }}đ</div>
                                <a href="#" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">Chưa có sản phẩm nào.</div>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection