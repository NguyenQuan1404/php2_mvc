@extends('layouts.admin')

@section('title', $title ?? 'Thêm Danh Mục')

@section('content')
<div class="card shadow col-md-8 mx-auto">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Thêm Danh Mục Mới</h4>
    </div>
    <div class="card-body p-4">
        {{-- SỬA: Thêm /admin vào action --}}
        <form action="/admin/category/store" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold">Tên Danh mục</label>
                {{-- Giữ nguyên name="tendanhmuc" để khớp với Controller --}}
                <input type="text" class="form-control" name="tendanhmuc" placeholder="Ví dụ: TF, FG,..." required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Hình ảnh</label>
                    <input type="file" class="form-control" name="hinhanh" accept="image/*" onchange="previewImage(this)">
                </div>
                <div class="col-md-4 text-center">
                    <div class="mt-2">
                         <img id="preview_img" src="#" alt="Preview" style="display: none; height: 100px; border-radius: 5px; border: 1px solid #ddd;">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Lưu lại
                </button>
                {{-- SỬA: Nút Hủy về trang admin --}}
                <a href="/admin/category" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection