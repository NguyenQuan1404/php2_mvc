@extends('layouts.admin')

@section('title', 'Quản lý Mã giảm giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold">Danh sách Coupon</h2>
    <a href="/coupon/create" class="btn btn-success shadow-sm">
        <i class="bi bi-plus-lg"></i> Thêm Mã Mới
    </a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light text-uppercase small fw-bold">
                    <tr>
                        <th>Mã Code</th>
                        <th>Loại giảm</th>
                        <th>Giá trị</th>
                        <th>Đơn tối thiểu</th>
                        <th>Số lượng</th>
                        <th>Hạn dùng</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $c)
                    <tr>
                        <td><span class="badge bg-info text-dark">{{ $c['code'] }}</span></td>
                        <td>
                            @if($c['type'] == 'percent')
                                <span class="badge bg-warning text-dark">Phần trăm (%)</span>
                            @else
                                <span class="badge bg-primary">Tiền mặt (VND)</span>
                            @endif
                        </td>
                        <td class="fw-bold text-danger">
                            {{ $c['type'] == 'percent' ? $c['value'].'%' : number_format($c['value']).' đ' }}
                        </td>
                        <td>{{ number_format($c['min_order_value']) }} đ</td>
                        <td>{{ $c['quantity'] }}</td>
                        <td>
                            <small>
                                {{ date('d/m/Y', strtotime($c['start_date'])) }} <br> 
                                <i class="bi bi-arrow-right-short"></i> 
                                {{ date('d/m/Y', strtotime($c['end_date'])) }}
                            </small>
                        </td>
                        <td>
                            @if($c['status'] == 1)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Đã khóa</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="/coupon/edit/{{ $c['id'] }}" class="btn btn-sm btn-outline-warning mx-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="/coupon/delete/{{ $c['id'] }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa mã này?');">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection