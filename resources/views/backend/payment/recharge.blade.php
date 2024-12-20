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
                        <input type="number" id="amount" name="price" class="form-control" step="0.01" required>
                        <small class="note">Lưu ý: Số tiền tối thiểu là 100.000đ và tối đa là 20.000.000.000đ</small>
                    </div>
                </div>
            </div>

            <!-- Số dư tài khoản chính -->
            <div class="form-group mb-3">
                <div class="row align-items-center">
                    <label class="col-md-3 fw-bold">Số dư tài khoản chính</label>
                    <div class="col-md-8">
                        <p class="mb-0">0 đ</p>
                    </div>
                </div>
            </div>

            <!-- Tổng tiền sau khi nạp -->
            <div class="form-group mb-3">
                <div class="row align-items-center">
                    <label class="col-md-3 fw-bold">Tổng tiền sau khi nạp</label>
                    <div class="col-md-8">
                        <p class="total-amount mb-0">100.000 đ</p>
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

