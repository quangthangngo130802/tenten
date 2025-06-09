@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <div class="card my-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin doanh nghiệp</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Form bên trái -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="businessCode" class="form-label">
                                    Mã số doanh nghiệp <small style="color: red;">*</small>
                                </label>
                                <input type="text" class="form-control" id="businessCode" name="businessCode"
                                    value="{{ old('businessCode', isset($business) ? $business->businessCode : '') }}" disabled
                                    placeholder="Nhập mã số doanh nghiệp">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="businessName" class="form-label">
                                    Tên doanh nghiệp <small style="color: red;">*</small>
                                </label>
                                <input type="text" class="form-control" id="businessName" name="businessName" disabled
                                    value="{{ old('businessName', isset($business) ? $business->businessName : '') }}"
                                    placeholder="Nhập tên doanh nghiệp">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="businessAddress" class="form-label">
                                    Địa chỉ doanh nghiệp <small style="color: red;">*</small>
                                </label>
                                <input type="text" class="form-control" id="businessAddress" name="businessAddress" disabled
                                    value="{{ old('businessAddress', isset($business) ? $business->businessAddress : '') }}"
                                    placeholder="Nhập địa chỉ doanh nghiệp">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="representative" class="form-label">
                                    Tên người đại diện <small style="color: red;">*</small>
                                </label>
                                <input type="text" class="form-control" id="representative" name="representative" disabled
                                    value="{{ old('representative', isset($business) ? $business->representative : '') }}"
                                    placeholder="Nhập tên người đại diện">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contactPhone" class="form-label">
                                    Số điện thoại liên hệ
                                </label>
                                <input type="text" class="form-control" id="contactPhone" name="contactPhone" disabled
                                    value="{{ old('contactPhone', isset($business) ? $business->contactPhone : '') }}"
                                    placeholder="Nhập số điện thoại">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contactEmail" class="form-label">
                                    Email liên hệ <small style="color: red;">*</small>
                                </label>
                                <input type="email" class="form-control" id="contactEmail" name="contactEmail" disabled
                                    value="{{ old('contactEmail', isset($business) ? $business->contactEmail : '') }}"
                                    placeholder="Nhập email liên hệ">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                    </div>


                    <!-- Ảnh hoặc iframe bên phải -->
                    <div class="col-md-12">
                        <ul class="nav nav-tabs" id="imageTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pdf-tab" data-bs-toggle="tab" data-bs-target="#pdf"
                                    type="button" role="tab">View</button>
                            </li>

                        </ul>
                        <div class="tab-content mt-2" id="imageTabContent">
                            <div class="tab-pane fade show active" id="pdf" role="tabpanel">
                                <div class="img-frame">
                                    <iframe id="fileIframe"
                                        src="{{ old('businessCode', isset($business) ? asset($business->file_path) : '') }}"
                                        width="100%" height="800px"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
