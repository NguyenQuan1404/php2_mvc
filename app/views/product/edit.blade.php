@extends('layouts.master')

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
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Giá gốc</label>
                    <input type="number" class="form-control" name="price" value="{{ $product['price'] }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Giá khuyến mãi</label>
                    <input type="number" class="form-control" name="sale_price" value="{{ $product['sale_price'] }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Số lượng</label>
                    <input type="number" class="form-control" name="quantity" value="{{ $product['quantity'] }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Thay đổi hình ảnh (Nếu có)</label>
                    <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this)">
                </div>
                <div class="col-md-6">
                     <p class="mb-1 text-muted small">Ảnh hiện tại:</p>
                    @if($product['image'])
                        <img id="preview_img" src="/uploads/products/{{ $product['image'] }}" height="120" class="border rounded">
                    @else
                        <img id="preview_img" src="#" style="display: none; height: 120px;" class="border rounded">
                        <span class="badge bg-secondary">Chưa có ảnh</span>
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