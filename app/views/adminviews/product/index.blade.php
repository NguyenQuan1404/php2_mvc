@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Quản lý Sản phẩm</h4>
        {{-- LINK: /admin/product/create --}}
        <a href="/admin/product/create" class="btn btn-light btn-sm fw-bold">
            <i class="bi bi-plus-circle"></i> Thêm Sản phẩm
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Hãng</th>
                        <th>Giá bán</th>
                        <th class="text-center">Kho</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $p)
                    <tr>
                        <td class="text-center">{{ $p['id'] }}</td>
                        <td class="text-center">
                            @if($p['image'])
                                <img src="/uploads/products/{{ $p['image'] }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </td>
                        <td class="fw-semibold text-primary">{{ $p['name'] }}</td>
                        <td><span class="badge bg-info text-dark">{{ $p['category_name'] ?? 'N/A' }}</span></td>
                        <td><small class="text-muted">{{ $p['brand_name'] ?? 'N/A' }}</small></td>
                        <td class="text-danger fw-bold">
                            @if($p['sale_price'] > 0)
                                {{ number_format($p['sale_price']) }}đ
                                <br><small class="text-muted text-decoration-line-through">{{ number_format($p['price']) }}đ</small>
                            @else
                                {{ number_format($p['price']) }}đ
                            @endif
                        </td>
                        <td class="text-center">{{ $p['quantity'] }}</td>
                        <td class="text-center">
                            @if($p['status'])
                                <span class="badge bg-success">Hiện</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- LINK: /admin/product/edit --}}
                            <a href="/admin/product/edit/{{ $p['id'] }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            {{-- ACTION: /admin/product/delete --}}
                            <form action="/admin/product/delete/{{ $p['id'] }}" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">Chưa có dữ liệu sản phẩm.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection