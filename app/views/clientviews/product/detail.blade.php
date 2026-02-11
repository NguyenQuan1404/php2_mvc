@extends('layouts.client')

@section('title', $title)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/products" class="text-decoration-none text-muted">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product['name'] }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Cột Ảnh Sản Phẩm -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <!-- Ảnh Chính -->
                <div class="position-relative overflow-hidden rounded bg-light text-center p-3">
                    <img id="mainImage" src="/uploads/products/{{ $product['image'] }}" class="img-fluid" style="max-height: 500px; object-fit: contain;" alt="{{ $product['name'] }}">
                    
                    @if($product['sale_price'] > 0)
                        <span class="position-absolute top-0 start-0 bg-danger text-white px-3 py-1 m-3 rounded fw-bold">
                            -{{ round((($product['price'] - $product['sale_price']) / $product['price']) * 100) }}%
                        </span>
                    @endif
                </div>

                <!-- List Ảnh Nhỏ (Variant Images) -->
                @if(!empty($variants))
                <div class="d-flex gap-2 mt-3 overflow-auto pb-2 px-2">
                    <!-- Ảnh gốc -->
                    <img src="/uploads/products/{{ $product['image'] }}" 
                         class="img-thumbnail cursor-pointer border-primary" 
                         style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                         onclick="changeImage(this.src)">
                    
                    <!-- Ảnh biến thể -->
                    @foreach($variants as $v)
                        @if(!empty($v['image']))
                        <img src="/uploads/products/{{ $v['image'] }}" 
                             class="img-thumbnail cursor-pointer" 
                             style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                             onclick="changeImage(this.src)">
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Cột Thông Tin -->
        <div class="col-md-6">
            <h5 class="text-uppercase text-muted small fw-bold">{{ $product['brand_name'] ?? 'Thương hiệu khác' }}</h5>
            <h1 class="fw-bold mb-3">{{ $product['name'] }}</h1>
            
            <!-- Giá bán -->
            <div class="mb-4">
                @if($product['sale_price'] > 0)
                    <h2 class="text-danger fw-bold d-inline me-2">{{ number_format($product['sale_price']) }}đ</h2>
                    <span class="text-muted text-decoration-line-through fs-5">{{ number_format($product['price']) }}đ</span>
                @else
                    <h2 class="text-danger fw-bold">{{ number_format($product['price']) }}đ</h2>
                @endif
            </div>

            <!-- Mô tả ngắn -->
            <p class="text-muted mb-4">
                {{ $product['short_description'] ?? 'Sản phẩm chất lượng cao, thiết kế hiện đại...' }}
            </p>

            <!-- Form Thêm vào giỏ (Sẽ xử lý sau) -->
            <form action="/cart/add" method="POST">
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                
                @if(!empty($variants) && count($variants) > 0)
                    <div class="mb-4">
                        <label class="form-label fw-bold">Chọn Phân loại (Màu - Size):</label>
                        <select class="form-select" name="variant_id" required>
                            <option value="">-- Chọn Size & Màu --</option>
                            @foreach($variants as $v)
                                <option value="{{ $v['id'] }}" data-img="{{ $v['image'] }}">
                                    Màu: {{ $v['color'] }} - Size: {{ $v['size'] }} (Còn {{ $v['quantity'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="d-flex gap-3 align-items-center mb-4">
                    <div class="input-group" style="width: 140px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()">-</button>
                        <input type="text" class="form-control text-center" name="quantity" id="qtyInput" value="1" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="incrementQty()">+</button>
                    </div>
                    
                    @if($product['quantity'] > 0)
                        <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                            <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg flex-grow-1" disabled>
                            Hết hàng
                        </button>
                    @endif
                </div>
            </form>

            <hr>
            <div class="d-flex gap-3 text-muted small">
                <span><i class="bi bi-check-circle-fill text-success me-1"></i> Chính hãng 100%</span>
                <span><i class="bi bi-arrow-repeat text-success me-1"></i> Đổi trả 7 ngày</span>
                <span><i class="bi bi-truck text-success me-1"></i> Giao hàng toàn quốc</span>
            </div>
        </div>
    </div>

    <!-- Tabs Mô tả & Đánh giá -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button">Mô tả chi tiết</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy-pane" type="button">Chính sách bảo hành</button>
                </li>
            </ul>
            <div class="tab-content border border-top-0 p-4 bg-white shadow-sm" id="myTabContent">
                <div class="tab-pane fade show active" id="desc-pane">
                    <div class="product-description">
                        @if(!empty($product['description']))
                            {!! nl2br($product['description']) !!}
                        @else
                            <p class="text-muted">Chưa có mô tả chi tiết cho sản phẩm này.</p>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="policy-pane">
                    <p>Bảo hành chính hãng 12 tháng. Hỗ trợ đổi size nếu không vừa trong vòng 7 ngày (giày chưa qua sử dụng).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    @if(!empty($relatedProducts))
    <div class="mt-5">
        <h3 class="fw-bold mb-4">Sản phẩm liên quan</h3>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach($relatedProducts as $rel)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <img src="/uploads/products/{{ $rel['image'] }}" class="card-img-top" alt="{{ $rel['name'] }}">
                        @if($rel['sale_price'] > 0)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ round((($rel['price'] - $rel['sale_price']) / $rel['price']) * 100) }}%</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-1 text-truncate">
                            <a href="/product/detail/{{ $rel['id'] }}" class="text-decoration-none text-dark stretched-link">{{ $rel['name'] }}</a>
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            @if($rel['sale_price'] > 0)
                                <span class="fw-bold text-danger">{{ number_format($rel['sale_price']) }}đ</span>
                                <small class="text-decoration-line-through text-muted">{{ number_format($rel['price']) }}đ</small>
                            @else
                                <span class="fw-bold text-danger">{{ number_format($rel['price']) }}đ</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    // Hàm đổi ảnh chính khi click ảnh nhỏ
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
    }

    // Tăng giảm số lượng
    function incrementQty() {
        var input = document.getElementById('qtyInput');
        var val = parseInt(input.value);
        input.value = val + 1;
    }

    function decrementQty() {
        var input = document.getElementById('qtyInput');
        var val = parseInt(input.value);
        if (val > 1) {
            input.value = val - 1;
        }
    }
</script>
@endsection