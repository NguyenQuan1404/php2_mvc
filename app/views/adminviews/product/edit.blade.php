@extends('layouts.admin')

@section('title', 'Sửa Sản phẩm')

@section('content')
<div class="card shadow">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">Sửa Sản phẩm: {{ $product['name'] }}</h4>
    </div>
    <div class="card-body">
        <form action="/product/update/{{ $product['id'] }}" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tên sản phẩm</label>
                    <input type="text" class="form-control" name="name" value="{{ $product['name'] }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Loại đế</label>
                    <select class="form-select" name="category_id" required>
                        @foreach($categories as $c)
                            <option value="{{ $c['id'] }}" {{ $c['id'] == $product['category_id'] ? 'selected' : '' }}>
                                {{ $c['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Thương hiệu</label>
                    <select class="form-select" name="brand_id">
                        <option value="">-- Chọn hãng --</option>
                        @foreach($brands as $b)
                            <option value="{{ $b['id'] }}" {{ $b['id'] == $product['brand_id'] ? 'selected' : '' }}>
                                {{ $b['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Giá gốc</label>
                    <input type="number" class="form-control" name="price" value="{{ $product['price'] }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Giá khuyến mãi</label>
                    <input type="number" class="form-control" name="sale_price" value="{{ $product['sale_price'] }}">
                </div>
                <input type="hidden" name="quantity" value="{{ $product['quantity'] }}">
            </div>

             <!-- PHẦN BIẾN THỂ (VARIANTS) -->
             <div class="mb-4 border p-3 rounded bg-light">
                <label class="form-label fw-bold text-dark"><i class="bi bi-layers"></i> Quản lý Biến thể</label>
                <div id="variants-area">
                    @forelse($variants as $index => $v)
                        <div class="row g-2 mb-3 variant-item align-items-center border-bottom pb-2">
                            <div class="col-md-2">
                                <label class="small text-muted">Size</label>
                                <input type="text" class="form-control" name="variants[{{$index}}][size]" value="{{ $v['size'] }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="small text-muted">Màu</label>
                                <input type="text" class="form-control" name="variants[{{$index}}][color]" value="{{ $v['color'] }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="small text-muted">Số lượng</label>
                                <input type="number" class="form-control" name="variants[{{$index}}][quantity]" value="{{ $v['quantity'] }}" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="small text-muted">Ảnh (Nếu muốn đổi)</label>
                                <input type="file" class="form-control mb-1" name="variants[{{$index}}][image]" accept="image/*">
                                <!-- Input ẩn để giữ lại tên ảnh cũ nếu không upload mới -->
                                <input type="hidden" name="variants[{{$index}}][old_image]" value="{{ $v['image'] ?? '' }}">
                                
                                @if(!empty($v['image']))
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/uploads/products/{{ $v['image'] }}" style="height: 40px; width: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;">
                                        <span class="small text-success"><i class="bi bi-check-circle"></i> Đã có ảnh</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger w-100 remove-variant"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    @empty
                        <!-- Dòng trống mặc định -->
                        <div class="row g-2 mb-3 variant-item align-items-end border-bottom pb-2">
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="variants[0][size]" placeholder="Size" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="variants[0][color]" placeholder="Màu sắc" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" name="variants[0][quantity]" value="{{ $product['quantity'] }}" required min="0">
                            </div>
                            <div class="col-md-4">
                                <input type="file" class="form-control" name="variants[0][image]" accept="image/*">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger w-100 remove-variant"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-outline-dark btn-sm mt-2" id="add-variant-btn">
                    <i class="bi bi-plus-lg"></i> Thêm biến thể khác
                </button>
            </div>
            <!-- KẾT THÚC PHẦN BIẾN THỂ -->

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Thay đổi hình ảnh chính</label>
                    <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this)">
                </div>
                <div class="col-md-6">
                     <p class="mb-1 text-muted small">Ảnh hiện tại:</p>
                    @if($product['image'])
                        <img id="preview_img" src="/uploads/products/{{ $product['image'] }}" height="120" class="border rounded">
                    @else
                        <img id="preview_img" src="#" style="display: none; height: 120px;" class="border rounded">
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả ngắn</label>
                <textarea class="form-control" name="short_description" rows="2">{{ $product['short_description'] }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả chi tiết</label>
                <textarea class="form-control" name="description" rows="4">{{ $product['description'] }}</textarea>
            </div>

            <div class="mb-4 form-check">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" class="form-check-input" name="status" value="1" {{ $product['status'] ? 'checked' : '' }} id="statusCheck">
                <label class="form-check-label" for="statusCheck">Hiển thị sản phẩm</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Cập nhật
                </button>
                <a href="/product" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sửa lỗi hiển thị đỏ trong Editor: Bọc output PHP trong dấu nháy và ép kiểu Number
        let variantIndex = Number('{{ count($variants) > 0 ? count($variants) : 1 }}');
        
        const container = document.getElementById('variants-area');
        const addBtn = document.getElementById('add-variant-btn');

        addBtn.addEventListener('click', function() {
            // Timestamp để tránh cache file input nếu cần, hoặc đơn giản là tạo ID duy nhất
            const html = `
                <div class="row g-2 mb-3 variant-item align-items-end border-bottom pb-2">
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="variants[${variantIndex}][size]" placeholder="Size" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="variants[${variantIndex}][color]" placeholder="Màu sắc" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="variants[${variantIndex}][quantity]" placeholder="SL" required min="0">
                    </div>
                    <div class="col-md-4">
                        <input type="file" class="form-control" name="variants[${variantIndex}][image]" accept="image/*">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger w-100 remove-variant"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            variantIndex++;
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-variant')) {
                const items = container.querySelectorAll('.variant-item');
                // Cho phép xóa thoải mái
                e.target.closest('.variant-item').remove();
            }
        });
    });
</script>
@endsection