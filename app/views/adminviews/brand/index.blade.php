@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Danh sách Thương hiệu</h4>
        <a href="/brand/create" class="btn btn-success btn-sm">
            <i class="bi bi-plus-circle"></i> Thêm mới
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-secondary">
                <tr>
                    <th class="text-center" width="5%">ID</th>
                    <th>Tên Thương hiệu</th>
                    <th>Mô tả</th>
                    <th class="text-center" width="20%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($brands as $item)
                <tr>
                    <td class="text-center">{{ $item['id'] }}</td>
                    <td class="fw-bold">{{ $item['name'] }}</td>
                    <td>{{ $item['description'] }}</td>
                    <td class="text-center">
                        <a href="/brand/edit/{{ $item['id'] }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="/brand/delete/{{ $item['id'] }}" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">Chưa có thương hiệu nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection