@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm mới')

@section('content')
<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Thêm Sản phẩm mới</h4>
    </div>
    <div class="card-body">
        <form action="/product/store" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tên sản phẩm</label>
                    <input type="text" class="form-control" name="name" required placeholder="VD: Giày Nike Mercurial...">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select class="form-select" name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Thương hiệu</label>
                    <select class="form-select" name="brand_id">
                        <option value="">-- Chọn hãng --</option>
                        @foreach($brands as $b)
                            <option value="{{ $b['id'] }}">{{ $b['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Giá gốc</label>
                    <input type="number" class="form-control" name="price" required min="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Giá khuyến mãi</label>
                    <input type="number" class="form-control" name="sale_price" value="0" min="0">
                </div>
            </div>

            <!-- PHẦN BIẾN THỂ (VARIANTS) -->
            <div class="mb-4 border p-3 rounded bg-light">
                <label class="form-label fw-bold text-primary"><i class="bi bi-layers"></i> Biến thể (Size - Màu - Ảnh)</label>
                <div id="variants-area">
                    <!-- Dòng mẫu đầu tiên -->
                    <div class="row g-2 mb-3 variant-item align-items-end border-bottom pb-2">
                        <div class="col-md-2">
                            <label class="small text-muted">Size</label>
                            <input type="text" class="form-control" name="variants[0][size]" placeholder="VD: 40" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted">Màu sắc</label>
                            <input type="text" class="form-control" name="variants[0][color]" placeholder="VD: Đỏ" required>
                        </div>
                        <div class="col-md-2">
                            <label class="small text-muted">Số lượng</label>
                            <input type="number" class="form-control" name="variants[0][quantity]" placeholder="0" required min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted">Ảnh biến thể (Tùy chọn)</label>
                            <input type="file" class="form-control" name="variants[0][image]" accept="image/*">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger w-100 remove-variant"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-variant-btn">
                    <i class="bi bi-plus-lg"></i> Thêm biến thể khác
                </button>
            </div>
            <!-- KẾT THÚC PHẦN BIẾN THỂ -->

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Hình ảnh đại diện (Chính)</label>
                    <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this)">
                </div>
                <div class="col-md-6">
                    <div class="mt-2">
                         <img id="preview_img" src="#" alt="Preview" style="display: none; max-height: 150px; border-radius: 5px; border: 1px solid #ddd;">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả ngắn</label>
                <textarea class="form-control" name="short_description" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả chi tiết</label>
                <textarea class="form-control" name="description" rows="4"></textarea>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" name="status" value="1" checked id="statusCheck">
                <label class="form-check-label" for="statusCheck">Hiển thị sản phẩm này</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Lưu Sản phẩm
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
        let variantIndex = 1;
        const container = document.getElementById('variants-area');
        const addBtn = document.getElementById('add-variant-btn');

        addBtn.addEventListener('click', function() {
            // Sử dụng template literals để tạo dòng mới
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
                if(items.length > 1) {
                    e.target.closest('.variant-item').remove();
                } else {
                    alert('Phải có ít nhất một biến thể!');
                }
            }
        });
    });
</script>
@endsection