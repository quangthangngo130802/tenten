@extends('backend.layouts.master')

{{-- @section('title', $title) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" rel="stylesheet">
@section('content')
    <form action="{{ isset($email) ? route('email.update', $email->id) : route('email.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @if (isset($email))
            @method('PUT')
        @endif
        <div class="card">
            <div class="card-body">

                <!-- Thông tin gói cloud -->
                <h5 class="section-title">Thông tin gói Email</h5>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="package_name" class="form-label">Tên gói</label>
                            <input type="text" class="form-control @error('package_name') is-invalid @enderror"
                                id="package_name" name="package_name" placeholder="Nhập tên gói"
                                value="{{ old('package_name', $email->package_name ?? '') }}" />
                            @error('package_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label for="storage" class="form-label">Dung lượng lưu trữ (GB) / 1 User</label>
                            <input type="number" class="form-control @error('storage') is-invalid @enderror" id="storage"
                                name="storage" placeholder="Nhập dung lượng lưu trữ"
                                value="{{ old('storage', $email->storage ?? '') }}" />
                            @error('storage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label for="domain_alias" class="form-label"> Domain Alias</label>
                            <input type="text" class="form-control @error('domain_alias') is-invalid @enderror"
                                id="domain_alias" name="domain_alias" placeholder="Nhập Domain Alias"
                                value="{{ old('domain_alias', $email->domain_alias ?? '') }}" />
                            @error('domain_alias')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label for="webmail" class="form-label"> Giao diện webmail</label>
                            <input type="text" class="form-control @error('webmail') is-invalid @enderror" id="webmail"
                                name="webmail" placeholder="Nhập Giao diện webmail"
                                value="{{ old('webmail', $email->webmail ?? '') }}" />
                            @error('webmail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="sender_hour" class="form-label">Email gửi/giờ / 1 tài khoản</label>
                            <input type="number" class="form-control @error('sender_hour') is-invalid @enderror"
                                id="sender_hour" name="sender_hour" placeholder="Nhập số lượng gửi/giờ / 1 tài khoản"
                                value="{{ old('sender_hour', $email->sender_hour ?? '') }}" />
                            @error('sender_hour')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group row">
                            <label for="backup" class="form-label">Tự động Backup (ngày)</label>
                            <input type="number" class="form-control @error('backup') is-invalid @enderror" id="backup"
                                name="backup" placeholder="Nhập thời gian để Backup"
                                value="{{ old('backup', $email->backup ?? '') }}" />
                            @error('backup')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label for="price" class="form-label">Giá (VNĐ/tháng)</label>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                                name="price" placeholder="Nhập giá" value="{{ old('price', $email->price ?? '') }}" />
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group row">
                            <label for="setting" class="form-label"> Cài đặt trên</label>
                            <input type="text" class="form-control @error('setting') is-invalid @enderror" id="setting"
                                name="setting" placeholder="Nhập cài đặt trên"
                                value="{{ old('setting', $email->setting ?? '') }}" />
                            @error('setting')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>


            </div>

        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach ($emailConfigs as $key =>  $item)
                    <div class="form-group col-md-4">
                        <label for="package_type" class="form-label">{{ $item->package_type }}/Năm</label>
                        <input type="text" class=" package_type form-control @error('package_type') is-invalid @enderror"
                            id="package_type_{{ $key }}"  name="package_type[{{ $item->id }}]"  placeholder="Nhập giá gói" value="{{ isset($pivotData[$item->id]) ? $pivotData[$item->id] : '' }}" />
                        @error('package_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

         <!-- Buttons -->
         <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success">{{ isset($email) ? 'Cập nhật' : 'Lưu' }}</button>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        .section-title {
            font-size: 1.2rem;
            font-weight: bold;
            padding-top: 20px;
            margin-bottom: 15px;
            padding: 10px;
            color: #fff;
            text-align: center;
        }

        .section-title:nth-of-type(1) {
            background-color: #4CAF50;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyPriceFormatter('#price');
            applyPriceFormatter('.package_type');
        });
    </script>
@endpush
