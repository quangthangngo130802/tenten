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
            <input type="hidden" name="id" value="{{ $company->id ?? '' }}">
            <div class="row">
                <div class="col-md-12">
                    @php
                        $fields = [
                            ['id' => 'company_name', 'label' => 'Tên công ty', 'type' => 'text', 'placeholder' => 'Nhập tên công ty'],
                            ['id' => 'company_address', 'label' => 'Địa chỉ công ty', 'type' => 'text', 'placeholder' => 'Nhập địa chỉ công ty'],
                            ['id' => 'company_phone', 'label' => 'Số điện thoại công ty', 'type' => 'text', 'placeholder' => 'Nhập số điện thoại công ty'],
                            ['id' => 'company_email', 'label' => 'Email công ty', 'type' => 'email', 'placeholder' => 'Nhập email công ty'],
                            ['id' => 'company_website', 'label' => 'Website công ty', 'type' => 'url', 'placeholder' => 'Nhập website công ty'],
                            ['id' => 'tax_id', 'label' => 'Mã số thuế', 'type' => 'text', 'placeholder' => 'Nhập mã số thuế'],
                            ['id' => 'vat_rate', 'label' => 'Tỷ lệ VAT', 'type' => 'text', 'placeholder' => 'Nhập tỷ lệ VAT'],
                            ['id' => 'representative_name', 'label' => 'Tên người đại diện', 'type' => 'text', 'placeholder' => 'Nhập tên người đại diện'],
                            ['id' => 'representative_position', 'label' => 'Chức vụ người đại diện', 'type' => 'text', 'placeholder' => 'Nhập chức vụ người đại diện'],
                            ['id' => 'representative_phone', 'label' => 'Số điện thoại người đại diện', 'type' => 'text', 'placeholder' => 'Nhập số điện thoại người đại diện'],
                            ['id' => 'representative_email', 'label' => 'Email người đại diện', 'type' => 'email', 'placeholder' => 'Nhập email người đại diện'],
                        ];
                    @endphp

                    @foreach ($fields as $field)
                        <div class="form-group row">
                            <label for="{{ $field['id'] }}" class="form-label col-md-2">{{ $field['label'] }}</label>
                            <div class="col-md-10">
                                <input type="{{ $field['type'] }}"
                                   class="form-control  @error($field['id']) is-invalid @enderror"
                                   id="{{ $field['id'] }}"
                                   name="{{ $field['id'] }}"
                                   placeholder="{{ $field['placeholder'] }}"
                                   value="{{ old($field['id'], $company->{$field['id']} ?? '') }}" />
                                   @error($field['id'])
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                        </div>
                    @endforeach
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
