@extends('backend.layouts.master')
@section('content')
<div class="content">
    <div class="card">
        <h3 class="text-center fw-bold">NẠP TIỀN VÀO TÀI KHOẢN</h3>
        <form method="POST" action="{{ route('payment.recharge.add') }}">
            @csrf
            <!-- Số tiền cần nạp -->
            <div class="form-group mb-3">
                <div class="row align-items-center">
                    <label for="amount" class="col-md-3 fw-bold">Số tiền cần nạp</label>
                    <div class="col-md-8">
                        <input type="text" id="amount" class="form-control">
                        <input type="hidden" id="amount_price" name="price" class="form-control">
                        <small class="note">Lưu ý: Số tiền tối thiểu là 100.000đ và tối đa là 20.000.000.000đ</small>
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
                        <p class="total-amount mb-0">0 đ</p>
                    </div>
                </div>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-submit btn-primary btn-pr px-4 py-2">Nạp tiền</button>
            </div>
        </form>

    </div>
</div>

@endsection
@push('styles')
<style>
    .card {
        padding: 20px;
    }

    /* .note {
        font-size: 14px;
        font-style: italic;
        color: #666;
    } */
    .total-amount {
        font-weight: bold;
        color: #ff6f00;
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const amountInput = document.getElementById("amount");
    const amountPriceInput = document.getElementById("amount_price");
    const totalAmountDisplay = document.querySelector(".total-amount");
    const note = document.querySelector(".note");
    const submitButton = document.querySelector(".btn-submit");

    const formatCurrency = (value) => {
        if (!value) return "0";
        return Number(value.replace(/\D/g, "")).toLocaleString("vi-VN");
    };

    const updateTotalAmount = () => {
        const wallet = {{ Auth::user()->wallet }};
        const inputValue = amountInput.value.replace(/\D/g, "");
        const newBalance = wallet + (parseInt(inputValue) || 0);
        totalAmountDisplay.textContent = formatCurrency(newBalance.toString()) + ' đ';
    };

    const validateAmount = () => {
        const rawValue = parseInt(amountPriceInput.value) || 0;
        if (rawValue < 100000 || rawValue > 20000000000) {
            note.style.color = "red";
            submitButton.disabled = true;
        } else {
            note.style.color = "black";
            submitButton.disabled = false;
        }
    };

    amountInput.addEventListener("input", (e) => {
        const rawValue = e.target.value.replace(/\D/g, ""); // Lấy giá trị chỉ có số
        e.target.value = formatCurrency(rawValue); // Hiển thị lại với định dạng tiền tệ
        amountPriceInput.value = rawValue; // Lưu giá trị gốc vào input ẩn
        updateTotalAmount(); // Cập nhật tổng số dư
        validateAmount(); // Kiểm tra hợp lệ
    });

    document.querySelector("form").addEventListener("submit", (e) => {
        if (submitButton.disabled) {
            e.preventDefault(); // Ngăn form gửi nếu không hợp lệ
        }
    });
});


</script>
@endpush
