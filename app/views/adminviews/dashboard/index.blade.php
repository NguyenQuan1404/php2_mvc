@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark">Xin chào, Admin! 👋</h2>
        <p class="text-muted">Đây là tổng quan tình hình kinh doanh của shop.</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Products -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 fw-semibold text-uppercase small">Sản phẩm</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['products'] }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fa-solid fa-box-open fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 fw-semibold text-uppercase small">Danh mục</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['categories'] }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="fa-solid fa-layer-group fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Brands -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 fw-semibold text-uppercase small">Thương hiệu</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['brands'] }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fa-solid fa-copyright fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 fw-semibold text-uppercase small">Người dùng</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['users'] }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                        <i class="fa-solid fa-users fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Quick Actions -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Hoạt động gần đây</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Hoạt động</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Admin vừa đăng nhập</td>
                            <td class="text-muted">Vừa xong</td>
                            <td><span class="badge bg-success">Success</span></td>
                        </tr>
                        <!-- Thêm data thật sau này -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Thao tác nhanh</h5>
            </div>
            <div class="card-body d-grid gap-2">
                {{-- SỬA LINK: Thêm /admin vào trước --}}
                <a href="/admin/product/create" class="btn btn-outline-primary text-start">
                    <i class="bi bi-plus-lg me-2"></i> Thêm sản phẩm mới
                </a>
                <a href="/admin/category/create" class="btn btn-outline-success text-start">
                    <i class="bi bi-folder-plus me-2"></i> Tạo danh mục mới
                </a>
                <a href="/admin/user/create" class="btn btn-outline-danger text-start">
                    <i class="bi bi-person-plus me-2"></i> Thêm nhân viên/user
                </a>
            </div>
        </div>
    </div>
</div>
@endsection