@extends('layouts.client')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>
    @if(empty($cart))
        <div class="alert alert-info">Giỏ hàng đang trống. <a href="/">Mua sắm ngay</a></div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    <tr>
                        <td style="width: 100px;">
                            <img src="/uploads/products/{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-fluid">
                        </td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ number_format($item['price']) }} đ</td>
                        <td style="width: 150px;">
                            <form action="/cart/update" method="POST" class="d-flex">
                                <input type="hidden" name="product_id" value="{{ $id }}">
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control me-2">
                                <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-sync"></i></button>
                            </form>
                        </td>
                        <td>{{ number_format($item['price'] * $item['quantity']) }} đ</td>
                        <td>
                            <a href="/cart/remove?id={{ $id }}" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm này?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                        <td colspan="2" class="fw-bold text-danger">{{ number_format($totalPrice) }} đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <a href="/" class="btn btn-secondary">Tiếp tục mua sắm</a>
            <a href="/checkout/index" class="btn btn-primary">Tiến hành thanh toán</a>
        </div>
    @endif
</div>
@endsection