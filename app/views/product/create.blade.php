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
                    <input type="text" class="form-control" name="name" required placeholder="Nhập tên sản phẩm...">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Loại đế (Danh mục)</label>
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
                    <input type="number" class="form-control" name="sale_price" value="0" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Số lượng kho</label>
                    <input type="number" class="form-control" name="quantity" value="10" required min="0">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Hình ảnh</label>
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