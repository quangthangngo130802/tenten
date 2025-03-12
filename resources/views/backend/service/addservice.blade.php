@extends('backend.layouts.master')

{{-- @section('title', $title) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" rel="stylesheet">
@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ route('service.add.submit', ['type' => $type]) }}" method="POST" enctype="multipart/form-data">
            @csrf


            <!-- Thông tin gói hosting -->
            <h5 class="section-title">Thông tin gói {{ $type }}</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email" class="form-label">Người dùng</label>
                        <select id="email" class="form-control select2" name="email">
                            <option value="">Chọn người dùng</option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->email }}" {{ old('email') == $customer->email ? 'selected' : '' }}>
                                {{ $customer->email }} - {{ $customer->full_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="package_name" class="form-label">Tên gói dịch vụ</label>
                        <select id="package_name" class="form-control select2" name="package_name">
                            <option value="">Chọn tên gói</option>
                            @foreach ($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_name') == $package->id ? 'selected' : '' }}>
                                {{ $package->package_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('package_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($type == 'hosting' || $type == 'email')
                    <div class="form-group">
                        <label for="domain" class="form-label">Domain</label>
                        <input type="text" class="form-control" id="domain" name="domain" placeholder="Nhập domain"
                            value="{{ old('domain') }}" />
                        @error('domain')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    @if ($type == 'cloud')
                    <div class="form-group">
                        <label for="os_id" class="form-label">Hệ điều hành</label>
                        <select id="os_id" class="form-control select2" name="os_id">
                            @foreach ($os_ids as $os_id)
                            <option value="{{ $os_id->id }}" {{ old('os_id') == $os_id->id ? 'selected' : '' }}>
                                {{ $os_id->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('os_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="backup" class="form-label">Back Up</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="backup_yes" name="backup" value="1" {{ old('backup') == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="backup_yes">Có</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="backup_no" name="backup" value="0" {{ old('backup') == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="backup_no">Không</label>
                        </div>
                        @error('backup')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="active_at" class="form-label">Ngày bắt đầu</label>
                            <input type="date" class="form-control @error('active_at') is-invalid @enderror" id="active_at" name="active_at"
                                value="{{ old('active_at') }}">
                            @error('active_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Ngày kết thúc (Tháng)</label>
                            <input type="number" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>


            <!-- Buttons -->
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-success">{{ isset($hosting) ? 'Cập nhật' : 'Lưu' }}</button>
            </div>
        </form>



    </div>
</div>


@endsection

@push('styles')
<!-- Link đến CSS của Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .select2 {
        width: 100%;
    }

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
    .invalid-feedback{
        display: block !important;
    }
</style>

@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
            // Khởi tạo Select2 cho các select
            $('#package_name').select2();
            $('#email').select2();
        });
</script>

@endpush
