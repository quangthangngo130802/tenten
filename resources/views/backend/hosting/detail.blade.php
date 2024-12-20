@extends('backend.layouts.master')

{{-- @section('title', $title) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" rel="stylesheet">
@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ isset($hosting) ? route('hosting.update', $hosting->id) : route('hosting.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf

            @if (isset($hosting))
            @method('PUT')
            @endif

            <!-- Thông tin gói hosting -->
            <h5 class="section-title">Thông tin gói hosting</h5>
            <div class="row">
                <div class="col-md-6">

                    <div class="form-group row">
                        <label for="package_name" class="form-label">Tên gói</label>
                        <input type="text" class="form-control @error('package_name') is-invalid @enderror"
                            id="package_name" name="package_name" placeholder="Nhập tên gói"
                            value="{{ old('package_name', $hosting->package_name ?? '') }}" />
                        @error('package_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="storage" class="form-label">Dung lượng</label>
                        <input type="text" class="form-control @error('storage') is-invalid @enderror" id="storage"
                            name="storage" placeholder="Nhập dung lượng"
                            value="{{ old('storage', $hosting->storage ?? '') }}" />
                        @error('storage')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="bandwidth" class="form-label">Băng thông</label>
                        <input type="text" class="form-control @error('bandwidth') is-invalid @enderror" id="bandwidth"
                            name="bandwidth" placeholder="Nhập băng thông"
                            value="{{ old('bandwidth', $hosting->bandwidth ?? '') }}" />
                        @error('bandwidth')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="website_limit" class="form-label">Giới hạn website</label>
                        <input type="number" class="form-control @error('website_limit') is-invalid @enderror"
                            id="website_limit" name="website_limit" placeholder="Nhập giới hạn website"
                            value="{{ old('website_limit', $hosting->website_limit ?? '') }}" />
                        @error('website_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                <div class="col-md-6">

                    <div class="form-group row">
                        <label for="ssl_included" class="form-label">Tích hợp SSL</label>
                        <select class="form-control @error('ssl_included') is-invalid @enderror" id="ssl_included"
                            name="ssl_included">
                            <option value="1" {{ (old('ssl_included', $hosting->ssl_included ?? '') == 1) ? 'selected' :
                                '' }}>Có</option>
                            <option value="0" {{ (old('ssl_included', $hosting->ssl_included ?? '') == 0) ? 'selected' :
                                '' }}>Không</option>
                        </select>
                        @error('ssl_included')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="price" class="form-label">Đơn giá (VNĐ/năm)</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                            name="price" placeholder="Nhập đơn giá" value="{{ old('price', $hosting->price ?? '') }}" />
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="tech_support" class="form-label">Hỗ trợ kỹ thuật</label>
                        <input type="text" class="form-control @error('tech_support') is-invalid @enderror"
                            id="tech_support" name="tech_support" placeholder="Nhập hỗ trợ kỹ thuật"
                            value="{{ old('tech_support', $hosting->tech_support ?? '') }}" />
                        @error('tech_support')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="backup_frequency" class="form-label">Tần suất backup</label>
                        <input type="text" class="form-control @error('backup_frequency') is-invalid @enderror"
                            id="backup_frequency" name="backup_frequency" placeholder="Nhập tần suất backup"
                            value="{{ old('backup_frequency', $hosting->backup_frequency ?? '') }}" />
                        @error('backup_frequency')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">{{ isset($hosting) ? 'Cập nhật' : 'Lưu' }}</button>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

<script src="{{ asset('ckfinder_php_3.7.0/ckfinder/ckfinder.js') }}"></script>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
