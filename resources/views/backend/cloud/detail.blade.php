@extends('backend.layouts.master')

{{-- @section('title', $title) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" rel="stylesheet">
@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ isset($cloud) ? route('cloud.update', $cloud->id) : route('cloud.store') }}"
            method="POST" enctype="multipart/form-data">
          @csrf
          @if (isset($cloud))
              @method('PUT')
          @endif

          <!-- Thông tin gói cloud -->
          <h5 class="section-title">Thông tin gói Cloud</h5>
          <div class="row">
              <div class="col-md-6">

                    <div class="form-group row">
                        <label for="type_id" class="form-label">Loại gói</label>
                        <select class="form-control @error('type_id') is-invalid @enderror" id="type_id" name="type_id">
                            <option value="">Chọn loại gói</option>
                            <option value="1" {{ old('type_id', $cloud->type_id ?? '') == 1 ? 'selected' : '' }}>Turbo Cloud Server</option>
                            <option value="2" {{ old('type_id', $cloud->type_id ?? '') == 2 ? 'selected' : '' }}>Windows Cloud Server</option>
                            <option value="3" {{ old('type_id', $cloud->type_id ?? '') == 3 ? 'selected' : '' }}>Linux Cloud Server</option>
                        </select>
                        @error('type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                  <div class="form-group row">
                      <label for="package_name" class="form-label">Tên gói</label>
                      <input type="text" class="form-control @error('package_name') is-invalid @enderror"
                             id="package_name" name="package_name" placeholder="Nhập tên gói"
                             value="{{ old('package_name', $cloud->package_name ?? '') }}" />
                      @error('package_name')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>

                  <div class="form-group row">
                      <label for="cpu" class="form-label">CPU</label>
                      <input type="text" class="form-control @error('cpu') is-invalid @enderror" id="cpu"
                             name="cpu" placeholder="Nhập thông số CPU"
                             value="{{ old('cpu', $cloud->cpu ?? '') }}" />
                      @error('cpu')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>

                  <div class="form-group row">
                      <label for="ram" class="form-label">RAM</label>
                      <input type="text" class="form-control @error('ram') is-invalid @enderror" id="ram"
                             name="ram" placeholder="Nhập dung lượng RAM"
                             value="{{ old('ram', $cloud->ram ?? '') }}" />
                      @error('ram')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>

              <div class="col-md-6">
                <div class="form-group row">
                    <label for="ssd" class="form-label">SSD</label>
                    <input type="text" class="form-control @error('ssd') is-invalid @enderror" id="ssd"
                           name="ssd" placeholder="Nhập dung lượng SSD"
                           value="{{ old('ssd', $cloud->ssd ?? '') }}" />
                    @error('ssd')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                  <div class="form-group row">
                      <label for="network" class="form-label">Mạng</label>
                      <input type="text" class="form-control @error('network') is-invalid @enderror" id="network"
                             name="network" placeholder="Nhập mạng"
                             value="{{ old('network', $cloud->network ?? '') }}" />
                      @error('network')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>

                  <div class="form-group row">
                      <label for="price" class="form-label">Giá (VNĐ/tháng)</label>
                      <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                             name="price" placeholder="Nhập giá"
                             value="{{ old('price', $cloud->price ?? '') }}" />
                      @error('price')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>

                  <div class="form-group row">
                      <label for="total_cost" class="form-label">Tổng tiền (VNĐ/năm)</label>
                      <input type="number" class="form-control @error('total_cost') is-invalid @enderror" id="total_cost"
                             name="total_cost" placeholder="Nhập tổng tiền"
                             value="{{ old('total_cost', $cloud->total_cost ?? '') }}" />
                      @error('total_cost')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
              </div>
          </div>

          <!-- Buttons -->
          <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-success">{{ isset($cloud) ? 'Cập nhật' : 'Lưu' }}</button>
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
