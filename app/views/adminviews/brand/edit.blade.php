@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="card col-md-6 mx-auto shadow">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Sửa Thương hiệu: {{ $brand['name'] }}</h4>
    </div>
    <div class="card-body p-4">
        {{-- SỬA ACTION: /admin/brand/update --}}
        <form action="/admin/brand/update/{{ $brand['id'] }}" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Tên Thương hiệu</label>
                <input type="text" class="form-control" name="name" value="{{ $brand['name'] }}" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea class="form-control" name="description" rows="3">{{ $brand['description'] }}</textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Cập nhật
                </button>
                {{-- SỬA LINK HỦY: /admin/brand --}}
                <a href="/admin/brand" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection