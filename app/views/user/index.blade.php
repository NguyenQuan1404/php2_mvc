@extends('layouts.master')

@section('title', $title)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Danh sách Người dùng</h4>
        <a href="/user/create" class="btn btn-light btn-sm fw-bold">
            <i class="bi bi-person-plus-fill"></i> Thêm User
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center">ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th class="text-center">Vai trò</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                <tr>
                    <td class="text-center">{{ $u['id'] }}</td>
                    <td class="fw-bold">{{ $u['fullname'] }}</td>
                    <td>{{ $u['email'] }}</td>
                    <td>{{ $u['phone'] }}</td>
                    <td class="text-center">
                        @if ($u['role'] == 1)
                            <span class="badge bg-danger">Admin</span>
                        @else
                            <span class="badge bg-primary">Khách hàng</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="/user/edit/{{ $u['id'] }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="/user/delete/{{ $u['id'] }}" method="POST" class="d-inline" onsubmit="return confirmDelete();">
                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Chưa có người dùng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection