@extends('backend.layouts.master')

{{-- @section('title', $title) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" rel="stylesheet">
@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ route('company.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Thông tin công ty -->
            <h5 class="section-title">Thông tin công ty</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="company_name" class="form-label">Tên công ty</label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                            id="company_name" name="company_name" placeholder="Nhập tên công ty"
                            value="{{ old('company_name', $company->company_name ?? '') }}" />
                        @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="company_address" class="form-label">Địa chỉ công ty</label>
                        <input type="text" class="form-control @error('company_address') is-invalid @enderror"
                            id="company_address" name="company_address" placeholder="Nhập địa chỉ công ty"
                            value="{{ old('company_address', $company->company_address ?? '') }}" />
                        @error('company_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="company_phone" class="form-label">Số điện thoại công ty</label>
                        <input type="text" class="form-control @error('company_phone') is-invalid @enderror"
                            id="company_phone" name="company_phone" placeholder="Nhập số điện thoại công ty"
                            value="{{ old('company_phone', $company->company_phone ?? '') }}" />
                        @error('company_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="company_email" class="form-label">Email công ty</label>
                        <input type="email" class="form-control @error('company_email') is-invalid @enderror"
                            id="company_email" name="company_email" placeholder="Nhập email công ty"
                            value="{{ old('company_email', $company->company_email ?? '') }}" />
                        @error('company_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="company_website" class="form-label">Website công ty</label>
                        <input type="url" class="form-control @error('company_website') is-invalid @enderror"
                            id="company_website" name="company_website" placeholder="Nhập website công ty"
                            value="{{ old('company_website', $company->company_website ?? '') }}" />
                        @error('company_website')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="tax_id" class="form-label">Mã số thuế</label>
                        <input type="text" class="form-control @error('tax_id') is-invalid @enderror" id="tax_id"
                            name="tax_id" placeholder="Nhập mã số thuế"
                            value="{{ old('tax_id', $company->tax_id ?? '') }}" />
                        @error('tax_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="vat_rate" class="form-label">Tỷ lệ VAT</label>
                        <input type="text" class="form-control @error('vat_rate') is-invalid @enderror" id="vat_rate"
                            name="vat_rate" placeholder="Nhập tỷ lệ VAT"
                            value="{{ old('vat_rate', $company->vat_rate ?? '') }}" />
                        @error('vat_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="form-group row">
                        <label for="representative_name" class="form-label">Tên người đại diện</label>
                        <input type="text" class="form-control @error('representative_name') is-invalid @enderror"
                            id="representative_name" name="representative_name" placeholder="Nhập tên người đại diện"
                            value="{{ old('representative_name', $company->representative_name ?? '') }}" />
                        @error('representative_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="representative_position" class="form-label">Chức vụ người đại diện</label>
                        <input type="text" class="form-control @error('representative_position') is-invalid @enderror"
                            id="representative_position" name="representative_position"
                            placeholder="Nhập chức vụ người đại diện"
                            value="{{ old('representative_position', $company->representative_position ?? '') }}" />
                        @error('representative_position')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="representative_phone" class="form-label">Số điện thoại người đại diện</label>
                        <input type="text" class="form-control @error('representative_phone') is-invalid @enderror"
                            id="representative_phone" name="representative_phone"
                            placeholder="Nhập số điện thoại người đại diện"
                            value="{{ old('representative_phone', $company->representative_phone ?? '') }}" />
                        @error('representative_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="representative_email" class="form-label">Email người đại diện</label>
                        <input type="email" class="form-control @error('representative_email') is-invalid @enderror"
                            id="representative_email" name="representative_email"
                            placeholder="Nhập email người đại diện"
                            value="{{ old('representative_email', $company->representative_email ?? '') }}" />
                        @error('representative_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">{{ isset($company) ? 'Cập nhật' : 'Lưu' }}</button>
            </div>
        </form>




    </div>
</div>


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
    const BASE_URL = "{{ url('/') }}";
</script>
{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

<script src="{{ asset('ckfinder_php_3.7.0/ckfinder/ckfinder.js') }}"></script> --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const imageInput = document.getElementById('image');
    const previewFrame = document.getElementById('preview-frame');

    // Khi click vào khung preview, kích hoạt input file
    previewFrame.addEventListener('click', () => {
        imageInput.click();
    });

    // Khi chọn file, hiển thị ảnh
    imageInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewFrame.innerHTML = `<img src="${e.target.result}" alt="Selected Image" style="max-width: 100%; height: auto;">`;
            };
            reader.readAsDataURL(file);
        } else {
            previewFrame.innerHTML = '<p class="text-muted">Click here to select an image</p>';
        }
    });

    // Nếu có ảnh được chọn sẵn (ví dụ: từ trước khi tải lại trang), hiển thị ảnh
    const currentImageSrc = '{{ old("image", asset("storage/" . ($category->logo ?? ""))) }}'; // Thay đổi này theo cách bạn lấy ảnh cũ từ server
    if (currentImageSrc) {
        previewFrame.innerHTML = `<img src="${currentImageSrc}" alt="Selected Image" style="max-width: 100%; height: auto;">`;
    }
});
</script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
    $(document).ready(function () {
    // Giá trị từ user đã được đổ ra từ backend
    var selectedProvince = $('#province').val();
    var selectedDistrict = "{{ $user->district ?? '' }}";
    var selectedWard = "{{ $user->ward ?? '' }}";

    // Hàm tải quận huyện
    function loadDistricts(provinceId, districtId = null, wardId = null) {
        if (provinceId) {
            $.ajax({
                url: '/get-districts',
                type: 'GET',
                data: { province_id: provinceId },
                success: function (data) {
                    var districts = data.districts;
                    var districtSelect = $('#district');
                    districtSelect.empty();
                    districtSelect.append('<option value="">Chọn quận huyện</option>');

                    districts.forEach(function (district) {
                        districtSelect.append('<option value="' + district.id + '" ' +
                            (district.id == districtId ? 'selected' : '') + '>' +
                            district.name + '</option>');
                    });

                    // Nếu có districtId, tự động tải danh sách phường/xã
                    if (districtId) {
                        loadWards(districtId, wardId);
                    } else {
                        $('#ward').empty().append('<option value="">Chọn phường xã</option>');
                    }
                }
            });
        } else {
            // Xoá danh sách quận/huyện và phường/xã khi không chọn tỉnh thành
            $('#district').empty().append('<option value="">Chọn quận huyện</option>');
            $('#ward').empty().append('<option value="">Chọn phường xã</option>');
        }
    }

    // Hàm tải xã/phường
    function loadWards(districtId, wardId = null) {
        if (districtId) {
            $.ajax({
                url: '/get-wards',
                type: 'GET',
                data: { district_id: districtId },
                success: function (data) {
                    var wards = data.wards;
                    var wardSelect = $('#ward');
                    wardSelect.empty();
                    wardSelect.append('<option value="">Chọn phường xã</option>');

                    wards.forEach(function (ward) {
                        wardSelect.append('<option value="' + ward.id + '" ' +
                            (ward.id == wardId ? 'selected' : '') + '>' +
                            ward.name + '</option>');
                    });
                }
            });
        } else {
            $('#ward').empty().append('<option value="">Chọn phường xã</option>');
        }
    }

    // Tải dữ liệu khi load trang nếu đã có dữ liệu từ user
    if (selectedProvince) {
        loadDistricts(selectedProvince, selectedDistrict, selectedWard);
    }

    // Khi chọn lại tỉnh thành
    $('#province').change(function () {
        var newProvince = $(this).val();
        loadDistricts(newProvince); // Reset quận huyện và xã/phường khi thay đổi tỉnh thành
    });

    // Khi chọn lại quận huyện
    $('#district').change(function () {
        var newDistrict = $(this).val();
        loadWards(newDistrict); // Reset xã/phường khi thay đổi quận huyện
    });
});

</script>
@endpush
