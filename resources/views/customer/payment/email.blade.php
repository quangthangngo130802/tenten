@extends('backend.layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-7">
            <div class="list-group">
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Gói</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-select" name="product_key" id="product_key" disabled>
                                @forelse ($emaillist as $item)
                                    @if ($item->id == $email->id)
                                        <option value="{{ $item->id }}" selected data-price="{{ $item->price }}">
                                            {{ $item->package_name }}
                                        </option>
                                    @endif
                                @empty
                                    <option value="">Không có gói nào</option>
                                @endforelse

                            </select>
                            {{-- <i>(*) Plan 2 được khuyên dùng khi tạo các website thông thường</i> --}}
                        </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Tên miền</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="domain" class="form-control" placeholder="Nhập tên miền" />
                        </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Gói</label>
                        </div>
                        <div class="col-sm-5">
                            <select class="form-select" name="package_type" id="package_type">
                                @foreach ($package_price as $item)
                                    <option value="{{ $item->emailConfig->id }}">{{ $item->emailConfig->package_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <select class="form-select" name="quantity" id="quantity">
                                {{-- <option value="12" attrvalue="{{ $email->price*12 }}">1 năm ({{
                                number_format($email->price*12, 0, ',', '.') }} đ)</option>
                            <option value="24" attrvalue="{{ $email->price*24 }}">2 năm ({{
                                number_format($email->price*24, 0, ',', '.') }} đ)</option>
                            <option value="36" attrvalue="{{ $email->price*36 }}">3 năm ({{
                                number_format($email->price*36, 0, ',', '.') }})</option>
                            <option value="48" attrvalue="{{ $email->price*48 }}">4 năm ({{
                                number_format($email->price*48, 0, ',', '.') }} đ)</option>
                            <option value="60" attrvalue="{{ $email->price*60 }}">5 năm ({{
                                number_format($email->price*60, 0, ',', '.') }} đ)</option> --}}
                            </select>
                        </div>
                    </div>
                </div>


                {{-- <div class="list-group-item">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Lựa chọn thêm</label>
                    </div>
                    <div class="col-sm-9">
                        <div class="backup_autom">
                            <div class="checkbu_area">
                                <input type="checkbox" name="checkbu_area" id="checkbu_area" value="75000">
                                <label for="checkbu_area">Tự động Backup</label>
                                <p>75.000 đ/tháng</p>
                            </div>
                            <div class="textwidget">
                                <p>Tính năng tự động back-up hàng tuần giúp dữ liệu của bạn được an toàn hơn. Bạn có
                                    thể khôi phục lại dữ liệu từ bản back-up đã được lưu trữ trong 3 tuần</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            </div>
        </div>
        <div class="col-sm-5">
            <div class="cart">
                <h3 class="cart-title">Tổng tiền Email Server</h3>
                <div class="cart-inner">
                    <div class="tg_bd_bt">
                        <div class="tg_cart_name">
                            {{-- <span class="cl1">CloudServer</span> --}}
                            <span class="cl2" style="color: #e76a38"
                                id="productNameUpdate">{{ $email->package_name }}</span>
                            {{-- <span class="cl4" id="OsNameUpdate">{{ $os[0]->name }}</span> --}}
                        </div>
                        <div class="rate" id="productPriceUpdate">
                            {{ number_format($price_email->price, 0, ',', '.') }} đ
                        </div>
                        <div class="py-2" id="backup" style="display: flex; justify-content: space-between">

                        </div>
                    </div>
                    <div class="cart-total">
                        <p>
                            <strong class="tg_fl">Tổng tiền chưa VAT: </strong>
                            <strong class="tg_fr" id="tong_price">{{ number_format($price_email->price, 0, ',', '.') }}
                                đ</strong>
                        </p>
                        <p>
                            <strong class="tg_fl">Tổng tiền VAT: </strong>
                            <strong class="tg_fr"
                                id="vat">{{ number_format(vat_amount($price_email->price), 0, ',', '.') }}</strong>
                        </p>
                        <p>
                            <strong class="tg_fl">Tổng cộng: </strong>
                            <strong class="tg_fr"><span id="tong_cong"
                                    style="color: red">{{ number_format($price_email->price, 0, ',', '.') }}
                                    đ</span></strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-4">
        <form action="{{ route('customer.email.addtocart') }}" method="post">
            @csrf
            <input type="text" name="product_id" id="product_id" value="{{ $email->id }}">
            <input type="text" name="numbertg" id="numbertg" value="12">
            <input type="text" name="time_type" id="time_type" value="month">
            <input type="text" name="domain" id="domain_new">
            <input type="text" name="totalprice" id="totalprice" value="{{ $price_email->price }}">
            <button class="btn btn-primary btn-lg">Tiếp tục</button>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .list-group-item {
            background-color: #e9f7ef;
            /* Màu nền nhẹ */
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 15px;
            display: inline-block !important;
        }

        .list-group-item label {
            font-weight: bold;
            color: #333;
        }

        .cart {
            /* padding: 20px; */
            /* border: 1px solid #28a745; */
            border-radius: 5px;
            background-color: #e0fff4;
            /* Màu nền cho cart */
        }

        .cart-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffff;
            margin-bottom: 15px;
            text-align: center;
            padding: 10px;
            background: #6fb521;
        }

        .cart-total {
            border: 1px solid #dcdcdc;
            padding: 10px 15px;
        }

        .cart-total p {
            font-weight: bold;
            display: flex;
            justify-content: space-between;


        }

        .total {
            color: #28a745;
            font-size: 1.5rem;
        }

        .cart-inner {
            padding: 20px;
        }

        .tg_cart_name span {
            font-weight: 600;
        }

        .rate {
            font-weight: bold;
            color: #dc3545;
            /* Màu đỏ cho giá */
        }

        .checkbu_area {
            display: flex;
            align-items: center;
        }

        #checkbu_area {
            margin-right: 10px;
        }

        .checkbu_area p {
            margin-left: 10px;
            color: #555;
            margin-bottom: 0px;
        }

        .checkbu_area label {
            display: inline-block;
            margin-left: 10px;
        }

        #productPriceUpdate {
            text-align: end;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            function fetchAndRenderQuantity() {
                let package_type = $('#package_type').val();
                let product_key = $('#product_key').val();

                $.ajax({
                    url: "{{ route('customer.email.package') }}",
                    type: 'GET',
                    data: {
                        package_type: package_type,
                        product_key: product_key
                    },
                    success: function(response) {
                        let price_package = response.data.price;
                        let $quantitySelect = $('#quantity');
                        let email = response.email;
                        console.log(email);
                        $('#productNameUpdate').text(email.package_name)
                        $('#numbertg').val(12);
                        valueText(price_package);
                        $quantitySelect.empty();

                        let durations = [12, 24, 36, 48, 60];
                        durations.forEach(function(months) {
                            let totalPrice = price_package * months / 12;
                            let formattedPrice = Number(totalPrice).toLocaleString('vi-VN');
                            let option =
                                `<option value="${months}" data-price="${totalPrice}">
                            ${months/12} năm (${formattedPrice} đ)
                        </option>`;
                            $quantitySelect.append(option);
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }


            fetchAndRenderQuantity();

            $('#package_type, #product_key').on('change', function() {
                fetchAndRenderQuantity();
            });

            $('#quantity').on('change', function() {
                let price = $(this).find(':selected').data('price');
                let months = $(this).val();
                $('#numbertg').val(months);
                valueText(price);
            });

            $('#domain').on('input', function() {
                let domain = $('#domain').val();
                $('#domain_new').val(domain);
                valueText(price);
            });

            function formatPrice(number) {
                return Number(number).toLocaleString('vi-VN');
            }

            const vatRate = {{ vat_rate() }}; // Ví dụ: 10 (10%)

            function valueText(value) {
                const vat = Math.round(value * vatRate / 100);
                const total = parseInt(value) + parseInt(vat);

                $('#productPriceUpdate').text(formatPrice(value) + ' đ');
                $('#tong_price').text(formatPrice(value) + ' đ');
                $('#vat').text(formatPrice(vat) + ' đ');
                $('#tong_cong').text(formatPrice(total) + ' đ');
                $('#totalprice').val(value); // tổng cộng để submit
            }
        });
    </script>
@endpush
