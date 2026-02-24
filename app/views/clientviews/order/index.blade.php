@extends('layouts.client')

@section('content')
<div class="container py-5">
    
    <!-- HIỂN THỊ THÔNG BÁO NẾU CÓ -->
    @if(isset($msg_success) && $msg_success)
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-start border-success border-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $msg_success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h2 class="mb-4">Lịch sử đơn hàng</h2>
    
    @if(empty($orders))
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="100" class="mb-3 opacity-50">
            <h4>Bạn chưa có đơn hàng nào</h4>
            <p class="text-muted">Hãy khám phá các sản phẩm tuyệt vời của chúng tôi</p>
            <a href="/" class="btn btn-primary mt-2">Mua sắm ngay</a>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-3">Mã đơn</th>
                            <th class="py-3">Ngày đặt</th>
                            <th class="py-3">Tổng tiền</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="py-3 text-end pe-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="ps-3 fw-bold">#{{ $order['id'] }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($order['created_at'])) }}</td>
                            <td class="text-danger fw-bold">{{ number_format($order['total_money']) }} đ</td>
                            <td>
                                @if($order['status'] == 'pending') 
                                    <span class="badge bg-warning text-dark border border-warning">
                                        <i class="fas fa-clock me-1"></i> Chờ xử lý
                                    </span>
                                @elseif($order['status'] == 'processing') 
                                    <span class="badge bg-primary border border-primary">
                                        <i class="fas fa-shipping-fast me-1"></i> Đang giao
                                    </span>
                                @elseif($order['status'] == 'completed') 
                                    <span class="badge bg-success border border-success">
                                        <i class="fas fa-check me-1"></i> Hoàn thành
                                    </span>
                                @elseif($order['status'] == 'cancelled') 
                                    <span class="badge bg-danger border border-danger">
                                        <i class="fas fa-times me-1"></i> Đã hủy
                                    </span>
                                @else 
                                    <span class="badge bg-secondary">{{ $order['status'] }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <a href="/myorder/detail?id={{ $order['id'] }}" class="btn btn-outline-primary btn-sm">
                                    Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection