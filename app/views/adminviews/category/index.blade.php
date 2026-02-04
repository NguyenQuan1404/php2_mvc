@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i>{{ $title }}</h4>
        <a href="/category/create" class="btn btn-light btn-sm fw-bold">
            <i class="bi bi-plus-circle me-1"></i> Thêm mới
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">ID</th>
                        <th>Tên Loại đế (Category)</th>
                        <th class="text-center">Hình ảnh</th>
                        <th class="text-center" width="20%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category as $item)
                        <tr>
                            <td class="text-center fw-bold">{{ $item['id'] }}</td>
                            <td class="fw-semibold text-primary">{{ $item['name'] }}</td>
                            <td class="text-center">
                                @if (!empty($item['image']))
                                    <img src="/uploads/categories/{{ $item['image'] }}" 
                                         class="img-thumb border" 
                                         alt="{{ $item['name'] }}">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="/category/edit/{{ $item['id'] }}" class="btn btn-warning btn-sm text-dark">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <form action="/category/delete/{{ $item['id'] }}" method="POST" onsubmit="return confirmDelete();">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection