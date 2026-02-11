@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="card col-md-6 mx-auto shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Thêm Thương hiệu mới</h4>
    </div>
    <div class="card-body p-4">
        {{-- SỬA ACTION: /admin/brand/store --}}
        <form action="/admin/brand/store" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Tên Thương hiệu</label>
                <input type="text" class="form-control" name="name" required placeholder="Ví dụ: Nike, Adidas...">
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Lưu
                </button>
                {{-- SỬA LINK HỦY: /admin/brand --}}
                <a href="/admin/brand" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection