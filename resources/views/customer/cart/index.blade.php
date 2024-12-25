@extends('backend.layouts.master')

@section('content')
<div class="content">
    <div class="row cart-container">
        <div class="col-md-8 p-4">
            <h1>Sản phẩm</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng cộng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cart->details as $item )
                    <tr>
                        <td><span class="close" data-id="{{ $item->id }}" style="color:red">x</span></td>
                        <td>
                            @php
                            $product = '';
                            if($item->type == 'hosting'){
                            $product = \App\Models\Hosting::find($item->product_id);
                            echo $product->package_name . ' ( ' . 'Hosting' . ' )';
                            }else if ($item->type == 'cloud') {
                            $product = \App\Models\Cloud::find($item->product_id);
                            echo $product->package_name . ' ( ' . 'Cloud' . ' )';
                            }

                            @endphp
                        </td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>
                            <div class="quantity-container">
                                <span class="decrease">-</span>
                                <input type="number" value="{{ $item->quantity }}" data-id="{{ $item->id }}" min="1"
                                    class="form-control quantity">
                                <span class="increase">+</span>
                            </div>
                        </td>
                        <td class="price_new">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫</td>
                    </tr>
                    @empty

                    @endforelse
                    <!-- Add more products as needed -->
                </tbody>
            </table>
        </div>
        <div class="col-md-4 p-4 bg-light">
            <h1>Tổng số giỏ hàng</h1>
            <table class="table">
                <tbody>
                    <tr>
                        <td><strong>Số lượng</strong></td>
                        <td class="total-quantity">{{ count($cart->details) }}</td>
                    </tr>
                    <tr>
                        <td>Tổng cộng</td>
                        <td class="total">{{ number_format($cart->total_price, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a class="btn checkout-button btn-block" id="checkout_cart">Tiến hành thanh toán</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .cart-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .total {
        font-weight: bold;
        font-size: 1.2em;
    }

    .checkout-button {
        background-color: #007bff;
        color: white;
    }

    .checkout-button:hover {
        background-color: #0056b3;
    }

    .quantity-container {
        display: flex;
        align-items: center;
    }

    .quantity-container input {
        width: 80px;
        text-align: center;
        padding: 5px;
        font-size: 16px;
        border: none;
        outline: none;
        box-shadow: none;
    }

    .quantity-container button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        font-size: 16px;
        cursor: pointer;
        margin: 0 5px;
    }

    .quantity-container button:hover {
        background-color: #0056b3;
    }

    .quantity-container button:focus {
        outline: none;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    input[type="number"] {
        border: none;
        outline: none;
        box-shadow: none;
    }

    .increase,
    .decrease,
    .close {
        cursor: pointer;
        font-size: 23px;
    }
</style>

@endpush

@push('scripts')

<script>
    $(document).ready(function() {
    $('.quantity-container .increase').on('click', function() {
        let input = $(this).siblings('.quantity');
        let currentValue = parseInt(input.val());
        input.val(currentValue + 1).trigger('input'); // Gửi sự kiện input
    });

    $('.quantity-container .decrease').on('click', function() {
        let input = $(this).siblings('.quantity');
        let currentValue = parseInt(input.val());
        if (currentValue > 1) {
            input.val(currentValue - 1).trigger('input'); // Gửi sự kiện input
        }
    });

    $('.quantity').on('input', function() {
        let quantity = $(this).val();
        let id = $(this).data('id');
        let pricePerItem = $(this).closest('tr').find('.price_new');
        $.ajax({
            url: `{{ route('update.quantity') }}`,
            type: 'POST',
            data: {
                id: id,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {

                console.log(response.message);
                $('.total').text(response.total_price);
                pricePerItem.text(response.price)
                // alert('Cập nhật số lượng thành công!');
            },
            error: function(xhr, status, error) {

                console.error(xhr.responseText);
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });

    $('.close').on('click', function() {
        let id = $(this).data('id');
        let btn = this;
        $.ajax({
            url: `{{ route('delete.item') }}`,
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('.total').text(response.total_price);
                btn.closest('tr').remove();
                $('.notification').text(response.count);
                $('.total-quantity').text(response.count);
                if (response.count === 0) {
                    Swal.fire({
                        title: 'Giỏ hàng trống!',
                        text: 'Bạn sẽ được chuyển đến trang Dashboard.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        // Điều hướng ra ngoài dashboard
                        window.location.href = '{{ route('dashboard') }}'; // Thay đổi route theo URL của dashboard
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    })

    $('#checkout_cart').on('click', function() {
        Swal.fire({
            title: 'Xác nhận đặt hàng',
            text: 'Bạn có chắc chắn muốn đặt hàng không?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Đặt hàng',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng xác nhận đặt hàng, tiến hành gửi yêu cầu Ajax
                $.ajax({
                    url: `{{ route('checkout.item') }}`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Đặt hàng thành công!',
                            text: 'Bạn sẽ được chuyển đến trang đơn hàng.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = '{{ route('customer.order.index', ['status' => 'nopayment']) }}'; // Thay đổi route theo URL của dashboard
                        });
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 400) {
                            Swal.fire({
                                title: 'Không đủ số dư. Vui lòng nạp thêm tiền vào tài khoản!',
                                text: xhr.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                window.location.href = '{{ route('payment.recharge') }}';
                            });
                        } else {
                            console.error(xhr.responseText);
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra, vui lòng thử lại.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            } else {
                // Nếu người dùng hủy bỏ, có thể hiển thị một thông báo hủy bỏ
                Swal.fire({
                    title: 'Đặt hàng đã bị hủy!',
                    text: 'Bạn đã hủy bỏ việc đặt hàng.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        });
    });


});

</script>
@endpush
