@extends('backend.layouts.master')
@section('content')
    <div class="content">
        <div class="card p-5">
            <h3 class="text-center fw-bold">NẠP TIỀN VÀO TÀI KHOẢN</h3>
            {{-- <form method="POST" action="{{ route('payment.recharge.add') }}"> --}}
            <form method="POST">
                <!-- Số tiền cần nạp -->
                <div class="form-group mb-3">
                    <div class="row align-items-center">
                        <label for="amount" class="col-md-3 fw-bold">Số tiền cần nạp</label>
                        <div class="col-md-8">
                            <input type="text" id="amount" class="form-control">
                            <input type="hidden" id="amount_price" name="price" class="form-control">
                            <small class="note" style="color: red">Lưu ý: Số tiền tối thiểu là 100.000đ và tối đa là
                                20.000.000.000đ</small>
                        </div>
                    </div>
                </div>

                <!-- Số dư tài khoản chính -->
                <div class="form-group mb-3">
                    <div class="row align-items-center">
                        <label class="col-md-3 fw-bold">Số dư tài khoản chính</label>
                        <div class="col-md-8">
                            <p class="mb-0">{{ number_format(Auth::user()->wallet, 0, ',', '.') }} đ</p>
                        </div>
                    </div>
                </div>

                <!-- Tổng tiền sau khi nạp -->
                <div class="form-group mb-3">
                    <div class="row align-items-center">
                        <label class="col-md-3 fw-bold">Tổng tiền sau khi nạp</label>
                        <div class="col-md-8">
                            <p class="total-amount mb-0" style="font-weight: bold;color: #ff6f00;">
                                {{ number_format(Auth::user()->wallet, 0, ',', '.') }} đ</p>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center">
                    <button type="button" class="btn btn-submit btn-primary btn-pr px-4 py-2 continue-btn" disabled>Nạp
                        tiền</button>
                </div>
            </form>

        </div>
    </div>

    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">QR Code</h5>

                </div>
                <div class="modal-body text-center">
                    <p>Vui lòng quét mã QR dưới đây để chuyển khoản:</p>
                    <img id="qrCodeImage" src="" alt="QR Code" style="width: 350px;" class="img-fluid" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="confirmTransaction">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            const $amountInput = $("#amount");
            const $amountPriceInput = $("#amount_price");
            const $totalAmountDisplay = $(".total-amount");
            const $note = $(".note");
            const $submitButton = $(".btn-submit");

            const wallet = {{ Auth::user()->wallet }};

            const formatCurrency = function(value) {
                if (!value) return "0";
                return Number(value.replace(/\D/g, "")).toLocaleString("vi-VN");
            };

            const updateTotalAmount = function() {
                const inputValue = $amountInput.val().replace(/\D/g, "");
                const newBalance = wallet + (parseInt(inputValue) || 0);
                $totalAmountDisplay.text(formatCurrency(newBalance.toString()) + " đ");
            };

            const validateAmount = function() {
                const rawValue = parseInt($amountPriceInput.val()) || 0;
                if (rawValue < 100000 || rawValue > 20000000000) {
                    $note.css("color", "red");
                    $submitButton.prop("disabled", true);
                } else {
                    $note.css("color", "black");
                    $submitButton.prop("disabled", false);
                }
            };

            $amountInput.on("input", function() {
                const rawValue = $(this).val().replace(/\D/g, "");
                $(this).val(formatCurrency(rawValue));
                $amountPriceInput.val(rawValue);
                updateTotalAmount();
                validateAmount();
            });

            $("form").on("submit", function(e) {
                if ($submitButton.prop("disabled")) {
                    e.preventDefault();
                }
            });


            $('.continue-btn').on('click', function() {

                var amount = $("#amount").val();
                var description = 'Nạp tiền vào tài khoản ' + '{{ Auth::user()->name }}';
                amount = parseInt(amount.replace(/,/g, '').replace(/\./g, ''), 10);



                $.ajax({
                    url: '{{ route('payment.qrCode') }}',
                    type: 'GET',
                    data: {
                        amount: amount,
                        description: description,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        $('#qrCodeImage').attr('src', response);
                        var modal = new bootstrap.Modal(document.getElementById('qrModal'));
                        modal.show();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Có lỗi xảy ra! Vui lòng thử lại.");
                    }
                });

            });

            $('#confirmTransaction').on('click', function() {
                let amount = $("#amount").val();
                amount = parseInt(amount.replace(/,/g, '').replace(/\./g, ''), 10);

                $.ajax({
                    url: '{{ route('payment.submit.recharge') }}',
                    type: 'POST',
                    data: {
                        amount: amount,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                       
                        const modalElement = document.getElementById('qrModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        modalInstance.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Nạp tiền thành công!',
                            text: response.message ||
                                'Số tiền đã được nạp vào tài khoản.',
                            confirmButtonText: 'OK'
                        }).then(() => {

                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Thất bại!',
                            text: 'Có lỗi xảy ra. Vui lòng thử lại.',
                            confirmButtonText: 'Đóng'
                        });
                    }
                });
            });

        });
    </script>
@endpush
