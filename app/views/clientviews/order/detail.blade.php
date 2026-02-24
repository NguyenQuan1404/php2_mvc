@extends('layouts.client')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết đơn hàng #{{ $order['id'] }}</h2>
        <!-- Cập nhật link quay lại -->
        <a href="/myorder/index" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Thông tin người nhận</div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> {{ $order['fullname'] }}</p>
                    <p><strong>Email:</strong> {{ $order['email'] }}</p>
                    <p><strong>SĐT:</strong> {{ $order['phone'] }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order['address'] }}</p>
                    <p><strong>Ghi chú:</strong> {{ $order['note'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">
                    Thông tin đơn hàng
                </div>

                <div class="card-body">
                    <p class="mb-2">
                        <strong>Ngày đặt:</strong>
                        {{ date('d/m/Y H:i', strtotime($order['created_at'])) }}
                    </p>

                    <p class="mb-3">
                        <strong>Trạng thái:</strong>
                        <span class="text-uppercase fw-bold text-primary">
                            {{ $order['status'] }}
                        </span>
                    </p>

                    <?php if ($order['status'] == 'pending'): ?>
                        <div class="text-end">
                            <a href="/myorder/cancel?id=<?= $order['id'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                                Hủy đơn
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>


    <div class="card">
        <div class="card-header">Sản phẩm đã mua</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $item)
                    <tr>
                        <td>
                            @if(isset($item['product_image']) && $item['product_image'])
                            <img src="/uploads/products/{{ $item['product_image'] }}" width="50" class="me-2">
                            @endif
                            {{ $item['product_name'] }}
                        </td>
                        <td>{{ number_format($item['price']) }} đ</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['total']) }} đ</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Tổng thanh toán:</td>
                        <td class="fw-bold text-danger">{{ number_format($order['total_money']) }} đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection