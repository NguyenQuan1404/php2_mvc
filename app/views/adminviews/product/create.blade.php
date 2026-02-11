@extends('layouts.admin')

@section('title', 'Thêm Sản phẩm mới')

@section('content')
<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Thêm Sản phẩm mới</h4>
    </div>
    <div class="card-body">
        {{-- ACTION: /admin/product/store --}}
        <form action="/admin/product/store" method="POST" enctype="multipart/form-data">
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
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Giá gốc</label>
                    <input type="number" class="form-control" name="price" required min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Giá khuyến mãi</label>
                    <input type="number" class="form-control" name="sale_price" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Ảnh đại diện</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả ngắn</label>
                <textarea class="form-control" name="short_description" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Chi tiết sản phẩm</label>
                <textarea class="form-control" name="description" rows="4"></textarea>
            </div>

            <div class="mb-3 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                <label class="form-check-label" for="status">Hiển thị ngay</label>
            </div>

            <hr class="my-4">
            <h5 class="text-primary fw-bold"><i class="bi bi-boxes"></i> Biến thể (Size/Màu)</h5>
            <div id="variants-container">
                <!-- Javascript sẽ render ô nhập vào đây -->
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-variant">
                <i class="bi bi-plus-lg"></i> Thêm biến thể
            </button>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Lưu Sản phẩm</button>
                {{-- LINK: /admin/product --}}
                <a href="/admin/product" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('variants-container');
        const addBtn = document.getElementById('add-variant');
        let variantIndex = 0;

        addBtn.addEventListener('click', function() {
            const html = `
                <div class="row g-2 mb-2 variant-item align-items-center">
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
                // Cho phép xóa
                e.target.closest('.variant-item').remove();
            }
        });
    });
</script>
@endsection