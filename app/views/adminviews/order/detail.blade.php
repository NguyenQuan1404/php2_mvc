@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between mt-4 mb-3">
        <h1>Chi tiết đơn hàng #{{ $order['id'] }}</h1>
        <a href="/admin/order/index" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="row">
        <!-- Cập nhật trạng thái -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Cập nhật trạng thái</div>
                <div class="card-body">
                    <form action="/admin/order/updateStatus" method="POST">
                        <input type="hidden" name="id" value="{{ $order['id'] }}">
                        <div class="mb-3">
                            <label class="form-label">Trạng thái hiện tại:</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order['status'] == 'pending' ? 'selected' : '' }}>Chờ xử lý (Pending)</option>
                                <option value="processing" {{ $order['status'] == 'processing' ? 'selected' : '' }}>Đang giao (Processing)</option>
                                <option value="completed" {{ $order['status'] == 'completed' ? 'selected' : '' }}>Hoàn thành (Completed)</option>
                                <option value="cancelled" {{ $order['status'] == 'cancelled' ? 'selected' : '' }}>Đã hủy (Cancelled)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Cập nhật</button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-3 shadow-sm">
                <div class="card-header">Thông tin khách hàng</div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> {{ $order['fullname'] }}</p>
                    <p><strong>Email:</strong> {{ $order['email'] }}</p>
                    <p><strong>SĐT:</strong> {{ $order['phone'] }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order['address'] }}</p>
                    <p><strong>Ghi chú:</strong> {{ $order['note'] }}</p>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">Sản phẩm trong đơn</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>SL</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $item)
                            <tr>
                                <td>
                                    {{ $item['product_name'] }}
                                </td>
                                <td>{{ number_format($item['price']) }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['total']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Tổng tiền:</td>
                                <td class="fw-bold text-danger">{{ number_format($order['total_money']) }} đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection