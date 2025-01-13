@extends('backend.layouts.master')

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="container bg-white p-4 border rounded shadow-sm">
            <h4 class="mb-4">THÔNG TIN ĐƠN HÀNG</h4>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Tên dịch vụ</th>
                        <th>Thời hạn đăng ký</th>
                        <th>Số tiền (chưa VAT)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart->details as $item)
                    <tr>
                        <td>
                            @php
                            $product = '';
                            if ($item->type == 'hosting') {
                            $product = \App\Models\Hosting::find($item->product_id);
                            echo $product->package_name . ' (Hosting)';

                            // Kiểm tra domain
                            $domainText = $item->domain ? $item->domain : 'Nhập tên miền của bạn';

                            echo '<div>
                                <span class="domain-text" style="color: red" data-id="' . $item->id . '">' . $domainText
                                    . '</span>
                                <div class="form-group" style="display: flex; justify-content: center">
                                    <input type="text" class="form-control domain-input" data-id="' . $item->id . '"
                                        placeholder="Vui lòng nhập tên miền" style="display: none; width: 80%;"
                                        value="' . $item->domain . '" />
                                </div>
                            </div>';
                            } elseif ($item->type == 'cloud') {
                            $product = \App\Models\Cloud::find($item->product_id);
                            echo $product->package_name . ' (Cloud)';
                            }
                            @endphp
                        </td>

                        <td>
                            <select class="select-form time_type" data-id="{{ $item->id }}">
                                @for ($i = 1; $i <= 5; $i++) @php $months=$i * 12; @endphp <option value="{{ $months }}"
                                    {{ $item->number == $months ? 'selected' : '' }}>
                                    {{ $i }} năm
                                    </option>
                                    @endfor
                            </select>
                        </td>

                        <td class="price_new"> {{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td data-id="{{ $item->id }}" class="close" style="margin-top: 13px;">
                            <i class="fa-solid fa-trash"></i>
                        </td>
                    </tr>
                    @endforeach

                    {{-- <tr>
                        <td>Wordpress Hosting v2 - StartUp</td>
                        <td>
                            <select class="form-select">
                                <option>1 tháng</option>
                                <option>6 tháng</option>
                                <option>1 năm</option>
                            </select>
                        </td>
                        <td>744.600 đ</td>
                    </tr> --}}
                </tbody>
            </table>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td>Giá gốc</td>
                        <td class="text-end total">{{ number_format($cart->total_price, 0, ',', '.') }} đ</td>
                    </tr>

                    <tr>
                        <td class="total-payment text-danger"><strong>Tổng tiền thanh toán</strong></td>
                        <td class="text-end total-payment text-danger total"><strong>{{
                                number_format($cart->total_price, 0, ',', '.') }} đ</strong></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center mt-4">
                <a href="{{ route('customer.order.payment') }}" class="btn btn-primary btn-lg">Tiếp tục</a>
            </div>
        </div>

    </div>
    <div class="col-md-5">
        <div class="container bg-white p-4 border rounded shadow-sm">
            <h4 class="mb-4">THÔNG TIN CHỦ SỞ HỮU</h4>
            <table class="table">
                <tr>
                    <th>Họ tên</th>
                    <td>{{ Auth::user()->full_name }}</td>
                </tr>
                <tr>
                    <th>Số CMND</th>
                    <td>{{ Auth::user()->identity_number }}</td>
                </tr>
                <tr>
                    <th>Quốc gia</th>
                    <td>VIET NAM</td>
                </tr>
                <tr>
                    <th>Tỉnh thành</th>
                    <td>{{ Auth::user()->province1->name }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td>{{ Auth::user()->address }} - {{ Auth::user()->ward1->name }} - {{ Auth::user()->district1->name
                        }}</td>
                </tr>
                <tr>
                    <th>Ngày sinh</th>
                    <td>{{ Auth::user()->birth_date }}</td>
                </tr>
                <tr>
                    <th>Giới tính</th>
                    <td>{{ Auth::user()->gender == 'male' ? 'Nam' : 'Nữ' }}</td>
                </tr>
                <tr>
                    <th>Điện thoại</th>
                    <td>{{ Auth::user()->phone_number }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ Auth::user()->email }}</td>
                </tr>
            </table>
            <p class="note text-danger">
                Trong trường hợp thông tin đăng ký không hợp lệ hoặc không khớp với giấy tờ xác nhận...
            </p>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    h4 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        letter-spacing: 0.5px;
    }

    .table th {
        background-color: #f1f1f1;
        font-size: 13px;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
    }

    .table td,
    .table th {
        vertical-align: middle;
        font-size: 11px !important;
        border: none !important;
    }

    select {
        font-size: 11px !important;
    }

    tr {
        border: none !important;
    }

    .table-borderless td {
        font-size: 14px;
    }

    .total-payment {
        font-size: 1.2rem;
    }

    .form-select-sm,
    .form-control {
        font-size: 13px;
        border-radius: 4px;
    }

    .input-group .btn {
        background-color: #007bff;
        border: none;
        font-size: 13px;
    }

    .input-group .btn:hover {
        background-color: #0056b3;
    }

    .text-secondary {
        color: #6c757d !important;
    }

    .note {
        font-size: 12px;
        color: #e74c3c;
    }

    select {
        padding: 3px 10px;
        font-size: 16px;
        border: 1px solid rgb(100, 94, 94) !important;
        border-radius: 7px;
    }

    .time_type {
        width: 110px;
        text-align: center;
    }
</style>

@endpush
@push('scripts')

<script>
    $(document).ready(function() {
        var APP_URL = '{{ env('APP_URL') }}';
    $('.close').on('click', function() {
        let id = $(this).data('id');
        let btn = this;
        $.ajax({
            url: APP_URL +'/delete-item',
            type: 'POST',
            data: {
                id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('.total').text(response.total_price);
                btn.closest('tr').remove();
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
        // alert(quantity);
        let id = $(this).data('id');
        let pricePerItem = $(this).closest('tr').find('.price_new');
        $.ajax({
            url: APP_URL +'/update-time',
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
                // alert('Cập nhật số lượng thành công!');
            },
            error: function(xhr, status, error) {

                console.error(xhr.responseText);
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        });
    });

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
                    url: APP_URL +'/checkout',
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


    $('.domain-text').on('click', function () {
        const id = $(this).data('id'); // Lấy ID của item
        $(this).hide(); // Ẩn đoạn text
        $(`.domain-input[data-id="${id}"]`).show().focus(); // Hiển thị input và focus vào
    });

    // Khi người dùng nhấn Enter hoặc rời khỏi input
    $('.domain-input').on('blur keydown', function (e) {
    if (e.type === 'blur' || e.key === 'Enter') {
        const id = $(this).data('id');

        const value = $(this).val(); // Lấy giá trị đã nhập

        if (value) {
            // Gửi giá trị mới lên server để lưu vào database
            $.ajax({
                url: '/save-domain', // Đường dẫn API lưu vào database
                method: 'POST',
                data: {
                    id: id,
                    domain: value,
                    _token: $('meta[name="csrf-token"]').attr('content') // Lấy CSRF token nếu dùng Laravel
                },
                success: function (response) {
                    // Hiển thị tên miền đã nhập nếu lưu thành công
                    $(`.domain-text[data-id="${id}"]`).text(value).show();
                },
                error: function () {
                    // Thông báo lỗi nếu lưu thất bại
                    alert('Có lỗi xảy ra khi lưu tên miền.');
                }
            });
        } else {
            // Nếu không có giá trị, hiển thị lại text mặc định
            $(`.domain-text[data-id="${id}"]`).text('Nhập tên miền của bạn').show();
        }

        $(this).hide(); // Ẩn input
    }
});


});

</script>
<script>
    // Thêm sự kiện click vào span chứa text domain
    document.querySelectorAll('.domain-text').forEach(function(span) {
        span.addEventListener('click', function() {
            var inputField = span.nextElementSibling.querySelector('input');
            // Hiển thị input và xóa chữ "Nhập tên miền của bạn"
            if (inputField.value === '' || inputField.value === 'Nhập tên miền của bạn') {
                inputField.value = '';  // xóa nội dung placeholder
                inputField.style.display = 'inline-block';  // hiển thị input
                span.style.display = 'none';  // ẩn span chứa text
            }
        });
    });

    // Cập nhật lại khi người dùng nhập tên miền
    document.querySelectorAll('.domain-input').forEach(function(input) {
        input.addEventListener('blur', function() {
            if (input.value === '') {
                // Nếu không nhập gì, hiển thị lại chữ "Nhập tên miền của bạn"
                input.style.display = 'none';
                input.previousElementSibling.style.display = 'inline-block';
                input.previousElementSibling.textContent = 'Nhập tên miền của bạn';
            }
        });
    });
</script>
@endpush
