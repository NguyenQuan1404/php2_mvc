@extends('layouts.client')

@section('title', 'Thanh toán')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 fw-bold text-uppercase">Thanh toán</h2>
    
    <div class="row">
        <!-- FORM THÔNG TIN (Bên Trái) -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <!-- QUAN TRỌNG: id="checkoutForm" để JS lấy đường dẫn chuẩn -->
                    <form action="process" method="POST" id="checkoutForm">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" value="{{ $user['fullname'] ?? '' }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user['email'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user['phone'] ?? '' }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ nhận hàng</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ $user['address'] ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú đơn hàng</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính..."></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- THÔNG TIN ĐƠN HÀNG (Bên Phải) -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-cart me-2 text-primary"></i>Đơn hàng của bạn</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        @if(!empty($cart))
                            @foreach($cart as $item)
                            <li class="list-group-item d-flex justify-content-between lh-sm px-0">
                                <div>
                                    <h6 class="my-0">{{ $item['name'] }}</h6>
                                    <small class="text-muted">SL: {{ $item['quantity'] }}</small>
                                </div>
                                <span class="text-muted">{{ number_format($item['price'] * $item['quantity']) }}đ</span>
                            </li>
                            @endforeach
                        @else
                            <li class="text-center">Giỏ hàng trống</li>
                        @endif
                    </ul>

                    <!-- KHU VỰC NHẬP MÃ GIẢM GIÁ -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="couponCode" placeholder="Nhập mã giảm giá">
                        <button class="btn btn-dark" type="button" onclick="applyCoupon()">Áp dụng</button>
                    </div>
                    <!-- Nơi hiện thông báo lỗi/thành công -->
                    <div id="couponMessage" class="small mb-3 fw-bold"></div>

                    <!-- TỔNG TIỀN -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong id="subTotal" data-value="{{ $totalPrice }}">{{ number_format($totalPrice) }}đ</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Giảm giá:</span>
                        <strong id="discountAmount">-0đ</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5">Tổng cộng:</span>
                        <strong class="h4 text-danger" id="finalTotal">{{ number_format($totalPrice) }}đ</strong>
                    </div>
                    
                    <button type="submit" form="checkoutForm" class="btn btn-success w-100 py-2 fw-bold text-uppercase">
                        Đặt hàng ngay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function applyCoupon() {
        const code = document.getElementById('couponCode').value.trim();
        const totalOrder = document.getElementById('subTotal').getAttribute('data-value');
        const msgDiv = document.getElementById('couponMessage');
        const discountDisplay = document.getElementById('discountAmount');
        const finalDisplay = document.getElementById('finalTotal');

        if (!code) {
            msgDiv.innerHTML = '<span class="text-danger">Vui lòng nhập mã!</span>';
            return;
        }

        msgDiv.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...</span>';

        // Lấy URL action từ form và thay thế 'process' bằng 'applyCoupon' để đảm bảo đúng đường dẫn tương đối
        const formAction = document.getElementById('checkoutForm').getAttribute('action'); 
        let ajaxUrl = formAction ? formAction.replace('process', 'applyCoupon') : 'applyCoupon';

        fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                code: code,
                total_order: totalOrder
            })
        })
        .then(async response => {
            const contentType = response.headers.get("content-type");
            
            // Nếu server trả về lỗi 500/404 hoặc không phải JSON (có thể là lỗi PHP in ra HTML)
            if (!response.ok || !contentType || !contentType.includes("application/json")) {
                const text = await response.text();
                console.error("Server Error Response:", text); // Log lỗi ra Console để debug
                throw new Error("Lỗi Server hoặc sai đường dẫn (Xem Console F12 để biết chi tiết)");
            }
            
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                msgDiv.innerHTML = `<span class="text-success"><i class="fas fa-check-circle"></i> ${data.message}</span>`;
                
                // Tính toán hiển thị lại tiền
                const discount = parseInt(data.discount);
                const currentTotal = parseInt(totalOrder);
                const newTotal = currentTotal - discount;

                const formatter = new Intl.NumberFormat('vi-VN');
                discountDisplay.innerText = '-' + formatter.format(discount) + 'đ';
                finalDisplay.innerText = formatter.format(newTotal) + 'đ';
                
                // Khóa ô nhập để tránh spam
                document.getElementById('couponCode').disabled = true;
            } else {
                msgDiv.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> ${data.message}</span>`;
                
                // Reset về 0 nếu mã sai
                const formatter = new Intl.NumberFormat('vi-VN');
                discountDisplay.innerText = '-0đ';
                finalDisplay.innerText = formatter.format(totalOrder) + 'đ';
            }
        })
        .catch(error => {
            console.error('Chi tiết lỗi JS:', error);
            msgDiv.innerHTML = `<span class="text-danger">Lỗi: ${error.message}</span>`;
        });
    }
</script>
@endsection