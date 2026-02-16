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
        <!-- Cột Ảnh Sản Phẩm (Bên trái) -->
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

        <!-- Cột Thông Tin (Bên phải) -->
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

            <!-- Form Thêm vào giỏ -->
            <form action="/cart/add" method="POST" class="mb-4">
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                
                @if(!empty($variants) && count($variants) > 0)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn Phân loại (Màu - Size):</label>
                        <select class="form-select" name="variant_id" id="variantSelect" required>
                            <option value="">-- Chọn Size & Màu --</option>
                            @foreach($variants as $v)
                                <option value="{{ $v['id'] }}" data-img="{{ $v['image'] }}">
                                    Màu: {{ $v['color'] }} - Size: {{ $v['size'] }} (Còn {{ $v['quantity'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="d-flex gap-3 align-items-center">
                    <div class="input-group" style="width: 140px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="decrementQty()">-</button>
                        <input type="text" class="form-control text-center" name="quantity" id="qtyInput" value="1" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="incrementQty()">+</button>
                    </div>
                    
                    @if($product['quantity'] > 0)
                        <button type="submit" class="btn btn-success btn-lg flex-grow-1 shadow-sm">
                            <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg flex-grow-1" disabled>
                            Hết hàng
                        </button>
                    @endif
                </div>
            </form>

            <div class="d-flex gap-3 text-muted small mt-4">
                <span><i class="bi bi-check-circle-fill text-success me-1"></i> Chính hãng 100%</span>
                <span><i class="bi bi-truck text-success me-1"></i> Giao hàng toàn quốc</span>
                <span><i class="bi bi-arrow-repeat text-success me-1"></i> Đổi trả dễ dàng</span>
            </div>
        </div>
    </div>

    <!-- Tabs Chi tiết (Mô tả, Thông số, Chính sách) -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button">Mô tả chi tiết</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button">Thông số kỹ thuật</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy-pane" type="button">Chính sách & Lời khuyên</button>
                </li>
            </ul>
            
            <div class="tab-content border border-top-0 p-4 bg-white shadow-sm rounded-bottom" id="myTabContent">
                <!-- Tab 1: Mô tả -->
                <div class="tab-pane fade show active" id="desc-pane">
                    <div class="product-description text-justify">
                        @if(!empty($product['description']))
                            {!! nl2br($product['description']) !!}
                        @else
                            <p class="text-muted fst-italic">Chưa có mô tả chi tiết cho sản phẩm này.</p>
                        @endif
                    </div>
                </div>

                <!-- Tab 2: Thông số (Placeholder) -->
                <div class="tab-pane fade" id="specs-pane">
                    <table class="table table-striped w-50">
                        <tbody>
                            <tr>
                                <th scope="row">Thương hiệu</th>
                                <td>{{ $product['brand_name'] ?? 'Đang cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Loại đế</th>
                                <td>{{ $product['category_name'] ?? 'Đang cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Chất liệu</th>
                                <td>Da tổng hợp cao cấp</td>
                            </tr>
                            <tr>
                                <th scope="row">Xuất xứ</th>
                                <td>Chính hãng</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab 3: Chính sách & Lời khuyên (Dữ liệu cứng) -->
                <div class="tab-pane fade" id="policy-pane">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-lightbulb-fill text-warning fs-4 me-3 mt-1"></i>
                                <div>
                                    <h5 class="fw-bold mb-2 text-dark">Lời khuyên chọn size</h5>
                                    <p class="text-muted text-justify">
                                        Đối với giày đá bóng, form giày thường ôm sát chân hơn giày đi chơi. 
                                        Chúng tôi khuyên bạn nên:
                                    </p>
                                    <ul class="text-muted ps-3">
                                        <li>Chọn lớn hơn <strong>0.5 - 1 size</strong> so với giày sneaker thông thường.</li>
                                        <li>Nếu chân bạn thuộc dạng <strong>bè ngang (chân to bề ngang)</strong>, hãy ưu tiên các dòng giày Wide Fit hoặc chọn lớn hơn 1 size.</li>
                                        <li>Nên mang tất thi đấu khi đo chân hoặc thử giày để có cảm giác chính xác nhất.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 border-start">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-shield-check text-success fs-4 me-3 mt-1"></i>
                                <div>
                                    <h5 class="fw-bold mb-2 text-dark">Chính sách bảo hành & Đổi trả</h5>
                                    <ul class="text-muted ps-3 mb-0">
                                        <li class="mb-2"><strong>Bảo hành keo trọn đời:</strong> Hỗ trợ dán keo miễn phí bất kể thời gian sử dụng.</li>
                                        <li class="mb-2"><strong>Bảo hành đế 12 tháng:</strong> 1 đổi 1 nếu gãy đế do lỗi nhà sản xuất.</li>
                                        <li class="mb-2"><strong>Đổi size miễn phí:</strong> Trong vòng 7 ngày kể từ khi nhận hàng (Yêu cầu: Giày sạch, chưa qua sử dụng, còn nguyên hộp/tem).</li>
                                        <li class="mb-2"><strong>Cam kết chính hãng:</strong> Hoàn tiền 100% và đền bù gấp 10 lần nếu phát hiện hàng giả (Fake).</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    @if(!empty($relatedProducts))
    <div class="mt-5 pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-uppercase mb-0">Sản phẩm liên quan</h3>
            <a href="/products" class="btn btn-outline-dark rounded-pill btn-sm px-4">Xem tất cả</a>
        </div>
        
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach($relatedProducts as $rel)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative p-2 bg-light">
                        <img src="/uploads/products/{{ $rel['image'] }}" class="card-img-top" style="height: 200px; object-fit: contain;" alt="{{ $rel['name'] }}">
                        @if($rel['sale_price'] > 0)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ round((($rel['price'] - $rel['sale_price']) / $rel['price']) * 100) }}%</span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-2 text-truncate">
                            <a href="/product/detail/{{ $rel['id'] }}" class="text-decoration-none text-dark stretched-link">{{ $rel['name'] }}</a>
                        </h6>
                        <div class="d-flex align-items-center gap-2 mt-auto">
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

    <!-- SẢN PHẨM ĐÃ XEM GẦN ĐÂY NẰM GỌN TRONG CONTAINER -->
    @if(isset($recentProducts) && count($recentProducts) > 0)
    <div class="mt-5 pt-5 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-uppercase mb-0"><i class="fas fa-history text-secondary me-2"></i>Sản phẩm đã xem</h3>
        </div>
        
        <div class="row row-cols-2 row-cols-md-5 g-4">
            @foreach($recentProducts as $item)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative overflow-hidden p-2 text-center bg-light">
                        <a href="/product/detail/{{ $item['id'] }}">
                            <img src="/uploads/products/{{ $item['image'] }}" class="img-fluid" style="height: 150px; object-fit: contain;" alt="{{ $item['name'] }}">
                        </a>
                    </div>
                    <div class="card-body p-3 text-center d-flex flex-column">
                        <h6 class="card-title text-truncate mb-2" style="font-size: 0.9rem;">
                            <a href="/product/detail/{{ $item['id'] }}" class="text-dark text-decoration-none">{{ $item['name'] }}</a>
                        </h6>
                        <span class="text-danger fw-bold mt-auto">{{ number_format($item['price'], 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div> <!-- Đóng thẻ container -->

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

    // Logic đổi ảnh theo biến thể
    document.addEventListener('DOMContentLoaded', function() {
        const variantSelect = document.getElementById('variantSelect');
        const mainImage = document.getElementById('mainImage');
        const originalSrc = mainImage.src;

        if (variantSelect) {
            variantSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const imgName = selectedOption.getAttribute('data-img');

                if (imgName && imgName.trim() !== '') {
                    mainImage.src = '/uploads/products/' + imgName;
                } else {
                    mainImage.src = originalSrc;
                }
            });
        }
    });
</script>

<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection