@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <form wire:submit.prevent="saveOrder" id="myForm">
            <div class="card mb-3">
                <div class="card-header">
                    <h5>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Tên khách hàng</label>
                            <input type="text" name="fullname" class="form-control" />
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" />
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" class="form-control" />
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row justify-content-around text-center">
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalHosting">Hosting</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalDomain">Domain</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEmail">Email</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalCloud">Cloud</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">Danh sách </h5>
                        {{-- <button type="button" class="btn btn-outline-primary" wire:click="addItem"><i
                                class="ti ti-recharging"></i> Thêm dòng
                        </button> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="overflow-auto">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1 me-2" style="min-width: 180px;">
                                    <label class="form-label">Tên dịch vụ</label>
                                </div>
                                <div class="flex-grow-1 me-2" style="min-width: 100px;">
                                    <label class="form-label">Loại</label>
                                </div>
                                <div class="flex-grow-1 me-2" style="min-width: 150px;">
                                    <label class="form-label">Domain</label>
                                </div>
                                <div class="flex-grow-1 me-2" style="min-width: 120px;">
                                    <label class="form-label">Thời gian/tháng</label>
                                </div>

                                <div class="flex-grow-1 me-2" style="min-width: 150px;">
                                    <label class="form-label">Thành tiền</label>
                                </div>
                                <div class="flex-grow-1 me-2" style="min-width: 200px;">
                                    <label class="form-label">Ghi chú</label>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>


            </div>




            {{-- <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">Thông tin thanh toán</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-end">
                        <div class="col-md-7">
                            <!-- Tạm tính -->
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Tạm tính:</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="number" class="form-control text-end"
                                            wire:model.defer="total_price" readonly wire:model="total_price"
                                            value="{{ number_format($total_price, 0, '.', ',') }}">
                                        <span class="input-group-text bg-transparent">đ</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Giảm giá -->
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Giảm giá:</label>
                                <div class="col-sm-9 d-flex">
                                    <div class="input-group mb-1">
                                        <input type="text" class="form-control" wire:model="discount_amount"
                                            min="0" name="discount_amount"
                                            value="{{ $this->discount_amount }}">
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    <div class="input-group mb-1 mx-1">
                                        <input type="number" class="form-control" wire:model="discountper"
                                            name="discountper" wire:change="discountcal" min="0"
                                            value="{{ $discountper }}">
                                        <span class="input-group-text" id="basic-addon">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Phí vận chuyển:</label>
                                <div class="col-sm-9 d-flex">
                                    <div class="input-group mb-1">
                                        <input type="text" class="form-control" wire:model="shipping_fee"
                                            min="0" name="shipping_fee" value="{{ $this->shipping_fee }}">
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    <div class="input-group mb-1 mx-1">
                                        <input type="number" class="form-control" wire:model="shippingper"
                                            min="0" name="shippingper" wire:change="shippingcal"
                                            value="{{ $shippingper }}">
                                        <span class="input-group-text" id="basic-addon">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Phí phát sinh:</label>
                                <div class="col-sm-9 d-flex">
                                    <div class="input-group mb-1">
                                        <input type="text" class="form-control" wire:model="additional_fee"
                                            min="0" name="additional_fee"
                                            value="{{ $this->additional_fee }}">
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    <div class="input-group mb-1 mx-1">
                                        <input type="number" class="form-control" wire:model="additionalper"
                                            min="0" name="additionalper" wire:change="additionalcal"
                                            value="{{ $additionalper }}">
                                        <span class="input-group-text" id="basic-addon">%</span>
                                    </div>
                                </div>
                            </div>


                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Thành tiền:</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control text-end" wire:model="final_price"
                                            value="{{ number_format($final_price, 0, '.', ',') }}" readonly>
                                        <span class="input-group-text bg-transparent">đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="floating">
                <div class="btn-group shadow-md">
                    <button type="submit" class="btn btn-warning"><i class="ti ti-cloud-storm me-1"></i>Lưu
                    </button>
                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item d-inline-flex text-warning @if ($order->status != 'open') disabled @endif"
                                id="orPrint58mm" href="/update-order-status-v1/{{ $order->external }}/processing">
                                <i class="ti ti-run me-1"></i> Xử lý
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-inline-flex text-success @if ($order->status != 'processing') disabled @endif"
                                id="orPrint58mm" href="/update-order-status-v1/{{ $order->external }}/complete">
                                <i class="ti ti-trending-up-3 me-1"></i> Hoàn tất
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-inline-flex @if ($order->status == 'draft') disabled @endif"
                                id="orPrint58mm" href="/print/{{ $order->external }}/58mm">
                                <i class="ti ti-printer me-1"></i> In 58mm
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-inline-flex @if ($order->status == 'draft') disabled @endif"
                                id="orPrintCate" href="/print/{{ $order->external }}/noprice">
                                <i class="ti ti-printer me-1"></i> In danh mục
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-inline-flex @if ($order->status == 'draft') disabled @endif"
                                id="orPrintQuote" href="/print/{{ $order->external }}/quote">
                                <i class="ti ti-printer me-1"></i> In báo giá
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-inline-flex @if ($order->status == 'draft') disabled @endif"
                                id="orPrintReceipt" href="/print/{{ $order->external }}/order">
                                <i class="ti ti-printer me-1"></i> In Phiếu
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a data-bs-toggle="modal" data-bs-target="#cancelModal"
                                class="dropdown-item mybtn-link-del" @if ($order->status == 'draft' || $order->status == 'cancel') disabled @endif
                                id="orcancel">
                                <i class="ti ti-credit-card-off me-1"></i> Huỷ đơn
                            </a>
                        </li>
                        <li>
                            <button class="dropdown-item d-inline-flex text-danger" type="button"
                                @if ($order->status != 'draft' && $order->status != 'open' && $order->status != 'pending') disabled @endif data-bs-toggle="modal"
                                data-bs-target="#deleteModal"><i class="ti ti-alert-triangle me-1"></i>Xóa
                            </button>
                        </li>
                    </ul>
                </div>
            </div> --}}
        </form>
        @include('backend.order.modal')
    </div>
@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('scripts')
    <script type="text/javascript"></script>
@endpush
