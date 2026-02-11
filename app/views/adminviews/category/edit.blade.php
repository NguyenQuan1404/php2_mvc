@extends('layouts.admin')

@section('title', $title ?? 'Chỉnh sửa Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Chỉnh Sửa Loại Đế</h4>
            </div>
            <div class="card-body p-4">
                {{-- SỬA: Thêm /admin vào action --}}
                <form action="/admin/category/update/{{ $category['id'] }}" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="tendanhmuc" class="form-label fw-bold">Tên Loại đế (TF, FG, IC...)</label>
                        {{-- name="tendanhmuc" khớp với Controller --}}
                        <input type="text" class="form-control" id="tendanhmuc" name="tendanhmuc" 
                               value="{{ $category['name'] }}" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="hinhanh" class="form-label fw-bold">Thay đổi hình ảnh (Nếu có)</label>
                            <input type="file" class="form-control" id="hinhanh" name="hinhanh" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="mb-1 text-muted small fw-bold">Ảnh hiện tại:</p>
                            @if (!empty($category['image']))
                                <img id="preview_img" 
                                     src="/uploads/categories/{{ $category['image'] }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 150px;">
                            @else
                                <img id="preview_img" src="#" style="display: none; max-height: 150px;" class="img-thumbnail">
                                <p class="small text-muted">Chưa có ảnh</p>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        {{-- SỬA: Nút Hủy về trang admin --}}
                        <a href="/admin/category" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Hủy bỏ
                        </a>
                        <button type="submit" class="btn btn-warning fw-bold">
                            <i class="bi bi-save"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection