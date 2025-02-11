@extends('backend.layouts.master')

@section('content')
<div class="row gy-4">
    <div class="col-lg-6 col-md-12">
        <div class="order-summary">
            <h4>Gia hạn dịch vụ</h4>
            <!-- Bắt đầu danh sách sản phẩm -->
            @forelse ($listrenews as $item)

            <div class=" mb-3 row renews" style="display: flex;justify-content: space-between">
                <p class="mb-1 col-md-4 ">
                    <?php
                        if ($item->type == 'hosting') {
                            $product = \App\Models\Hosting::find($item->product_id);
                            $backup = '';
                        } elseif ($item->type == 'cloud') {
                            $product = \App\Models\Cloud::find($item->product_id);
                            $backup = ' - ' . $item->os->name;
                        } elseif ($item->type == 'email') {
                            $product = \App\Models\Email::find($item->product_id);
                            $backup = ' '; // Giả sử `email_service` là quan hệ tới thông tin dịch vụ email.
                        }elseif ($item->type == 'domain') {
                            $product_domain = $item->domain.$item->domain_extension;

                        }
                        if($item->type == 'domain'){
                            echo $product_domain. ' ('.Str::ucfirst($item->type).') ';
                        }else {
                            echo $product->package_name.$backup .' ('.Str::ucfirst($item->type).') ';
                        }
                    ?>
                </p>
                <select class="select-form time_type" data-id="{{ $item->id }}" {{ isset($id) ? 'disabled' : '' }}
                    style="width: 100px; text-align: center; padding: 5px 5px;" class="col-md-2">
                    @if ($item->type === 'hosting' || $item->type === 'domain')
                    <option value="12" {{ $item->number == 12 ? 'selected' : '' }}>1 năm</option>
                    <option value="24" {{ $item->number == 24 ? 'selected' : '' }}>2 năm</option>
                    <option value="36" {{ $item->number == 36 ? 'selected' : '' }}>3 năm</option>
                    <option value="48" {{ $item->number == 48 ? 'selected' : '' }}>4 năm</option>
                    <option value="60" {{ $item->number == 60 ? 'selected' : '' }}>5 năm</option>
                    @else
                    <option value="1" {{ $item->number == 1 ? 'selected' : '' }}>1 tháng</option>
                    <option value="3" {{ $item->number == 3 ? 'selected' : '' }}>3 tháng</option>
                    <option value="6" {{ $item->number == 6 ? 'selected' : '' }}>6 tháng</option>
                    <option value="12" {{ $item->number == 12 ? 'selected' : '' }}>1 năm</option>
                    <option value="24" {{ $item->number == 24 ? 'selected' : '' }}>2 năm</option>
                    <option value="36" {{ $item->number == 36 ? 'selected' : '' }}>3 năm</option>
                    <option value="48" {{ $item->number == 48 ? 'selected' : '' }}>4 năm</option>
                    <option value="60" {{ $item->number == 60 ? 'selected' : '' }}>5 năm</option>
                    @endif
                </select>
                <span class="fw-bold text-primary col-md-3 text-end price_new">{{ number_format($item->price, 0, ',',
                    '.') }}
                    đ</span>

                <span class="col-md-1 close" style="cursor: pointer; {{ isset($id) ? 'display: none;' : '' }}"
                    data-id="{{ $item->id }}">
                    <i class="fas fa-trash"></i>
                </span>

            </div>
            @empty

            @endforelse

            <hr>
            <div class="d-flex justify-content-between mt-3">
                <span>Tổng thanh toán:</span>
                <span class="total">{{ number_format($sum, 0, ',', '.') }} đ</span>
            </div>
        </div>
        <div class="text-center mt-4">
            <button class="btn btn-primary btn-lg payment_end">Xác nhận</button>
        </div>
    </div>

    <div class="col-lg-6 col-md-12">
        <div class="payment-methods">
            <h4>Chọn Phương Thức Thanh Toán</h4>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment" id="vnpay" value="qr" checked>
                <label class="form-check-label" for="vnpay">Thanh toán QR code</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment" id="momo" value="vi">
                <label class="form-check-label" for="momo">Thanh toán bằng tài khoản trong ví</label>
            </div>
        </div>
    </div>
</div>


@endsection

@push('styles')
<style>
    #invoiceModal strong {
        color: red;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
    }

    .modal-title {
        font-weight: bold;
    }

    .modal-footer {
        background-color: #f1f1f1;
    }

    .form-label {
        font-weight: bold;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        border: none;
    }

    .small {
        color: red;
    }

    .small li {
        margin-bottom: 0.5rem;
    }

    .input-group .form-control {
        border-right: none;
    }

    .input-group .btn {
        border-left: none;
    }

    .modal-content {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }


    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: none;
        border-radius: 10px;
    }

    .order-summary,
    .payment-methods,
    .invoice-options {
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .order-summary h4,
    .payment-methods h4,
    .invoice-options h5 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 20px;
        border-bottom: 2px solid #e1e1e1;
        padding-bottom: 10px;
    }

    .total {
        font-weight: bold;
        color: #d9534f;
    }

    .form-check-label {
        font-weight: 500;
        font-size: 0.95rem;
    }

    .note {
        font-size: 0.9rem;
        color: #d9534f;
        margin-left: 24px;
    }

    p {
        font-size: 0.85rem;
        /* color: #666; */
    }

    /* Responsive */
    @media (max-width: 768px) {

        .order-summary h4,
        .payment-methods h4,
        .invoice-options h5 {
            font-size: 1.2rem;
        }

        .order-summary,
        .payment-methods,
        .invoice-options {
            padding: 15px;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Thêm trong file HTML -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        var APP_URL = '{{ env('APP_URL') }}';
        var id =   '{{ $id ?? null }}';
        // alert(id);
        $('.close').on('click', function() {
            let id = $(this).data('id');
            // alert(id);
            let btn = this;
            $.ajax({
                // url: `{{ route('renews.delete.item') }}`,
                url: APP_URL + '/renews-delete-item',
                type: 'POST',
                data: {
                    id: id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('.total').text(response.total_price);
                    btn.closest('div.renews').remove();
                    $('.notification').text(response.count);
                    // $('.total-quantity').text(response.count);
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

        $('.time_type').on('change', function() {

            let quantity = $(this).val();
            let id = $(this).data('id');
            let pricePerItem = $(this).closest('div.renews').find('.price_new');
            $.ajax({
                // url: `{{ route('renews.update.time') }}`,
                url: APP_URL + '/renews-update-time',
                type: 'POST',
                data: {
                    id: id,
                    quantity: quantity,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response.price);
                    console.log(response.message);
                    $('.total').text(response.total_price);
                    pricePerItem.text(response.price)
                },
                error: function(xhr, status, error) {

                    console.error(xhr.responseText);
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            });
        });

        $('.payment_end').click(function () {
            var pttt = $('input[name=payment]:checked').val();

            // alert(id);
            if (pttt === 'qr') {
                // Chuyển đến trang quét QR
                if(id != null){
                    // alert('ok');
                    window.location.href = "{{ route('customer.order.create.payment.enews', ['id' => '__id__']) }}".replace('__id__', id);
                }else{
                    window.location.href = "{{ route('customer.order.create.payment.enews') }}";
                }

            } else if (pttt === 'vi') {
                $url = APP_URL + '/customer/order/thanh-toan/gia-han';
                if(id){
                    $url += '/' + id;
                }
                // alert($url);
                $.ajax({
                    // url: '{{ route('customer.order.thanhtoan') }}',
                    url: $url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Laravel CSRF
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Thanh toán thành công!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = APP_URL + '/dashboard';
                            });
                        } else {
                            Swal.fire({
                                title: 'Số dư ví không đủ!',
                                text: 'Bạn có muốn chuyển sang thanh toán bằng QR không?',
                                icon: 'warning',
                                showCancelButton: true,
                                showDenyButton: true,
                                confirmButtonText: 'Chuyển sang QR',
                                denyButtonText: 'Nạp tiền',
                                cancelButtonText: 'Hủy'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('input[name=payment][value="qr"]').prop('checked', true);
                                    $('input[name=payment][value="vi"]').prop('checked', false);
                                    if(id != null){
                                        window.location.href = "{{ route('customer.order.create.payment.enews', ['id' => '__id__']) }}".replace('__id__', id);
                                    }
                                    window.location.href = "{{ route('customer.order.create.payment.enews') }}";
                                } else if (result.isDenied) {
                                    window.location.href = '{{ route('payment.recharge') }}';
                                }
                            });

                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Lỗi hệ thống',
                            text: 'Có lỗi xảy ra. Vui lòng thử lại sau.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Thông báo',
                    text: 'Vui lòng chọn phương thức thanh toán hợp lệ.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

</script>
@endpush
