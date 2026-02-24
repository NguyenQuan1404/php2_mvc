@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý đơn hàng</h1>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Danh sách đơn hàng
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order['id'] }}</td>
                        <td>
                            {{ $order['fullname'] }}<br>
                            <small>{{ $order['phone'] }}</small>
                        </td>
                        <td>{{ date('d/m/Y H:i', strtotime($order['created_at'])) }}</td>
                        <td>{{ number_format($order['total_money']) }} đ</td>
                        <td>
                            @if($order['status'] == 'pending') <span class="badge bg-warning text-dark">Chờ xử lý</span>
                            @elseif($order['status'] == 'completed') <span class="badge bg-success">Hoàn thành</span>
                            @elseif($order['status'] == 'cancelled') <span class="badge bg-danger">Đã hủy</span>
                            @else <span class="badge bg-info">{{ $order['status'] }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="/admin/order/detail?id={{ $order['id'] }}" class="btn btn-sm btn-primary">Chi tiết</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection