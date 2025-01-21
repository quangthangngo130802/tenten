@extends('backend.layouts.master')

@section('content')
<div class="row gy-4">
    <!-- Hàng đầu tiên: Thông Tin Đơn Hàng và Chọn Phương Thức Thanh Toán -->
    <div class="col-lg-6 col-md-12">
        <div class="order-summary">
            <h4>Thông Tin Đơn Hàng</h4>
            @forelse ($order_new->orderDetail as $item)
            <div class=" mb-2 row" style="display: flex;justify-content: space-between">
                <p class="mb-1 col-md-6 ">
                    <?php
                        if($item->type == 'hosting'){
                            $product = \App\Models\Hosting::find($item->product_id);
                            $backup = '';
                            $domain = $item->domain;
                        } else if($item->type == 'cloud') {
                            $product = \App\Models\Cloud::find($item->product_id);
                            $backup = ' - '.$item->os->name;
                            $domain = '';
                        }else {
                            $product = \App\Models\Email::find($item->product_id);
                            $backup = '';
                            $domain = $item->domain;
                        }
                        echo $product->package_name.$backup.' ( ' .$domain. ' )';
                    ?>
                </p>
                <select disabled style="width: 100px; text-align: center; padding: 5px 5px;" class="col-md-1">
                    <option>{{$item->number }} tháng</option>
                </select>
                <span class="fw-bold text-primary col-md-3 text-end">{{ number_format($item->price, 0, ',', '.') }}
                    đ</span>
            </div>
            @empty
            <p>No order details available.</p>
            @endforelse

            <hr>
            {{-- <div class="d-flex justify-content-between">
                <span>VAT:</span>
                <span>172,800 đ</span>
            </div> --}}
            <div class="d-flex justify-content-between total mt-3">
                <span>Tổng thanh toán:</span>
                <span data-id="{{ $order_new->id }}" data-price="{{ $order_new->amount }}">{{
                    number_format($order_new->amount, 0, ',', '.') }}
                    đ</span>
            </div>
        </div>
        <div class="text-center mt-4">
            {{-- <form action="{{ route('customer.order.payment') }}" method="post">
                @csrf --}}
                <button class="btn btn-primary btn-lg payment_end">Xác nhận</button>
                {{--
            </form> --}}
        </div>

    </div>
    <div class="col-lg-6 col-md-12">
        <div class="payment-methods">
            <h4>Chọn Phương Thức Thanh Toán</h4>
            {{-- <div class="form-check">
                <input class="form-check-input" type="radio" name="payment" id="bank-transfer" checked>
                <label class="form-check-label" for="bank-transfer">Chuyển khoản ngân hàng</label>
                <p class="note">Miễn phí - thanh toán và kích hoạt dịch vụ nhanh</p>
            </div> --}}
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment" id="vnpay" value="qr" checked>
                <label class="form-check-label" for="vnpay">Thanh toán QR code </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment" id="momo" value="vi">
                <label class="form-check-label" for="momo">Thanh toán bằng tài khoản trong ví</label>
            </div>
            {{-- <div class="form-check">
                <input class="form-check-input" type="radio" name="payment" id="zalopay">
                <label class="form-check-label" for="zalopay">Thanh toán QR code ZaloPay</label>
            </div> --}}
        </div>

        <div class="col-12 mt-3">
            <div class="invoice-options">
                <h5>Yêu Cầu Xuất Hóa Đơn</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="invoice" id="no-invoice" value="no" checked>
                    <label class="form-check-label" for="no-invoice">Không xuất hóa đơn</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="invoice" id="yes-invoice" value="yes">
                    <label class="form-check-label" for="yes-invoice">Có xuất hóa đơn</label>
                </div>
                <p>Căn cứ vào Quyết định số 1830/QĐ-BTC ngày 20/9/2021 của Bộ Tài Chính...</p>
            </div>
        </div>
    </div>
    <!-- Hàng thứ hai: Yêu Cầu Xuất Hóa Đơn -->

</div>

{{-- <div class="container mt-5">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#invoiceModal">Mở thông tin xuất hóa
        đơn</button>
</div> --}}

<!-- Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Thông tin xuất hóa đơn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" accept-charset="utf-8" id="formBill" class="form-horizontal" autocomplete="off">
                    <div class="mb-3">
                        <label for="bill-ownerid" class="form-label"><strong>*</strong> Mã số thuế:</label>
                        <div class="">
                            <input type="text" name="bill_ownerid" class="form-control" id="bill-ownerid" required>
                            {{-- <button class="btn btn-secondary">Kiểm tra</button> --}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="bill-name" class="form-label"><strong>*</strong> Tên Tổ chức/Cá nhân <span>(đầy đủ,
                                có dấu):</span></label>
                        <input type="text" name="bill_name" class="form-control" id="bill-name" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="bill-persion" class="form-label">Người đại diện <span>(Nếu là tổ chức) (đầy đủ, có
                                dấu):</span></label>
                        <input type="text" name="bill_persion" class="form-control" id="bill-persion">
                    </div> --}}
                    {{-- <div class="mb-3">
                        <label for="bill-office" class="form-label">Chức vụ:</label>
                        <input type="text" name="bill_office" class="form-control" id="bill-office">
                    </div> --}}
                    <div class="mb-3">
                        <label for="bill-address" class="form-label"><strong>*</strong> Địa chỉ xuất hóa đơn:</label>
                        <input type="text" name="bill_address" class="form-control" id="bill-address" required>
                    </div>
                    <div class="mb-3">
                        <label for="bill-email" class="form-label"><strong>*</strong> Email nhận hóa đơn điện
                            tử:</label>
                        <input type="email" name="bill_email" class="form-control" id="bill-email" required>
                    </div>
                    <div class="mb-3">
                        <label for="bill-phone" class="form-label"><strong>*</strong> Số điện thoại liên hệ:</label>
                        <input type="tel" name="bill_phone" class="form-control" id="bill-phone" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="bill_area" class="form-label"><strong>*</strong> Giao dịch với văn phòng
                            tại:</label>
                        <select name="bill_area" id="bill_area" class="form-select">
                            <option value="Hà Nội" selected>Hà Nội</option>
                            <option value="TP. Hồ Chí Minh">TP. HCM</option>
                        </select>
                    </div> --}}
                </form>

                <div class="mt-4">
                    <h6>Lưu ý:</h6>
                    <ul class="small">
                        <li>Quý khách cần nhập đầy đủ, chính xác và hoàn toàn chịu trách nhiệm về những thông tin xuất
                            hóa đơn đã cung cấp.</li>
                        <li>Chúng tôi sẽ không liên hệ với Quý khách để xác nhận lại các thông tin trên.</li>
                        {{-- <li>Chúng tôi không hỗ trợ HỦY và/hoặc xuất lại hóa đơn VAT đối với đơn hàng này.</li>
                        <li>Chúng tôi từ chối xuất hóa đơn VAT đối với tên miền .VN, nếu chủ thể xuất hóa đơn không phải
                            là
                            chủ thể của tên miền.</li>
                        <li>Riêng sản phẩm SSL và Email Gsuite - Chúng tôi chỉ hỗ trợ xuất hoá đơn trong ngày đối với
                            đơn
                            hàng đăng ký trước ngày 26 hàng tháng. Những đơn hàng từ ngày 26 đến ngày 30/31 sẽ được
                            chuyển sang xuất hoá đơn cho tháng kế tiếp khi Quý khách đăng ký có lấy hoá đơn.</li> --}}
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="confirmInvoice">Xác nhận</button>
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
        $('input[name="invoice"]').on('change', function () {
            if ($('#yes-invoice').is(':checked')) {
                    // Đảm bảo sử dụng đúng cú pháp cho Bootstrap 5
                var myModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                myModal.show();
            }
        });

        let isConfirmed = false;
        var formData = [];
        $('#confirmInvoice').on('click', function () {
            var myModal = bootstrap.Modal.getInstance(document.getElementById('invoiceModal'));

            // Kiểm tra các trường bắt buộc
            var isValid = true;
            var requiredFields = [
                '#bill-ownerid',
                '#bill-name',
                '#bill-address',
                '#bill-email',
                '#bill-phone',
                '#bill_area'
            ];

            requiredFields.forEach(function(field) {
                var $field = $(field);
                if ($field.val() === '') {
                    isValid = false;
                    $field.addClass('is-invalid'); // Thêm class lỗi
                    $field.siblings('.invalid-feedback').show(); // Hiển thị thông báo lỗi
                } else {
                    $field.removeClass('is-invalid'); // Loại bỏ class lỗi
                    $field.siblings('.invalid-feedback').hide(); // Ẩn thông báo lỗi
                }
            });

            // Nếu form hợp lệ, tiếp tục
            if (isValid) {
                myModal.hide(); // Ẩn modal
                isConfirmed = true;

                $('#yes-invoice').prop('checked', true);
                $('#no-invoice').prop('checked', false);

                formData = [
                    $('#bill-ownerid').val(), // Mã số thuế
                    $('#bill-name').val(), // Tên tổ chức/cá nhân
                    $('#bill-persion').val(), // Người đại diện
                    $('#bill-office').val(), // Chức vụ
                    $('#bill-address').val(), // Địa chỉ xuất hóa đơn
                    $('#bill-email').val(), // Email nhận hóa đơn
                    $('#bill-phone').val(), // Số điện thoại
                    $('#bill_area').val() // Giao dịch với văn phòng tại
                ];
            } else {
                // Nếu form không hợp lệ, không làm gì cả
                alert("Vui lòng điền đầy đủ thông tin bắt buộc!");
            }
        });


        // Khi modal bị ẩn, kiểm tra nếu đã xác nhận thì không thay đổi trạng thái radio
        $('#invoiceModal').on('hidden.bs.modal', function () {
            if (!isConfirmed) {
                // Nếu chưa xác nhận, chọn lại "Không xuất hóa đơn"
                $('#no-invoice').prop('checked', true);
            }
            // Reset biến xác nhận
            isConfirmed = false;
        });

        $('.payment_end').click(function () {
            var pttt = $('input[name=payment]:checked').val();
            var xsd = $('input[name=invoice]:checked').val();
            var id = document.querySelector('span[data-id]').getAttribute('data-id');
            var price = document.querySelector('span[data-price]').getAttribute('data-price');

            if (pttt === 'qr') {
                // Chuyển đến trang quét QR
                window.location.href = "{{ route('customer.order.create.payment', ['id' => '__ID__', 'xsd' => '__XSD__']) }}".replace('__ID__', id).replace('__XSD__', xsd);
            } else if (pttt === 'vi') {

                $.ajax({
                    url: APP_URL + '/customer/order/thanh-toan/don-hang',
                    method: 'POST',
                    data: {
                        id : id,
                        price: price,
                        invoice: xsd,
                        thongtin :{
                            ownerid: formData[0], // Mã số thuế
                            name: formData[1], // tên cá nhân tổ chức
                            person: formData[2], // Người đại diện
                            office: formData[3], // Chức vụ
                            address: formData[4], // Địa chỉ xuất hóa đơn
                            email: formData[5], // Email nhận hóa đơn
                            phone: formData[6], // Số điện thoại
                            area: formData[7],  // Giao dịch với văn phòng tại
                        }
                    },
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
                                if(xsd == 'yes'){
                                    const pdfUrl = response.pdf_url;
                                    const link = document.createElement('a');
                                    link.href = pdfUrl;
                                    link.download = 'receipt_order.pdf';
                                    link.click();
                                }
                                window.location.href = "{{ route('customer.order.show', ['id' => '__ID__']) }}".replace('__ID__', id);


                            });
                        } else {
                            Swal.fire({
                                title: 'Số dư ví không đủ!',
                                text: 'Bạn có muốn chuyển sang thanh toán bằng QR không?',
                                icon: 'warning',
                                showCancelButton: true, // Hiển thị nút Hủy
                                showDenyButton: true,   // Hiển thị nút Nạp tiền
                                confirmButtonText: 'Chuyển sang QR',
                                denyButtonText: 'Nạp tiền',
                                cancelButtonText: 'Hủy'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Chuyển radio sang QR và bỏ chọn ví
                                    $('input[name=payment][value="qr"]').prop('checked', true);
                                    $('input[name=payment][value="vi"]').prop('checked', false);
                                    // Chuyển hướng tới trang QR Payment
                                    window.location.href = "{{ route('customer.order.create.payment.enews', ['id' => '__ID__', 'xsd' => '__XSD__']) }}".replace('__ID__', id).replace('__XSD__', xsd);
                                } else if (result.isDenied) {
                                    // Xử lý hành động nạp tiền
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
