<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <title>Đăng ký</title>
</head>

<body>
    <style>
        .container {
            max-width: 900px !important;
        }

        .invalid-feedback {
            display: block !important;
        }

        .g-recaptcha {
            max-width: 100%;
        }
    </style>
    <div class="container mt-3">
        <h2 class="mb-4" style="text-align: center">Đăng ký tài khoản</h2>
        <form action="{{ route('submit.register') }}" method="post">
            @csrf
            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Tên đăng nhập <span style="color:red"> *
                    </span></label>
                <div class="col-sm-8">
                    <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}">
                    <div id="username-error" class="text-danger mt-2"></div>
                    @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">Mật khẩu <span style="color:red"> *
                    </span></label>
                <div class="col-sm-8">
                    <input type="password" name="password" class="form-control" id="password">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="full_name" class="col-sm-4 col-form-label">Họ và tên<span style="color:red"> *
                    </span></label>
                <div class="col-sm-8">
                    <input type="text" name="full_name" class="form-control" id="full_name"
                        value="{{ old('full_name') }}">
                    @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Giới tính <span style="color:red"> * </span></label>
                <div class="col-sm-8">
                    <label class="mr-3"><input type="radio" name="gender" value="male" {{ old('gender')=='male'
                            ? 'checked' : '' }}> Nam</label>
                    <label><input type="radio" name="gender" value="female" {{ old('gender')=='female' ? 'checked' : ''
                            }}> Nữ</label>
                    @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="dob" class="col-sm-4 col-form-label">Ngày sinh <span style="color:red"> * </span></label>
                <div class="col-sm-8">
                    <input type="date" name="birth_date" class="form-control" id="birth_date"
                        value="{{ old('birth_date') }}">
                    @error('birth_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="idCard" class="col-sm-4 col-form-label">CMND/CCCD/Hộ chiếu <span style="color:red"> *
                    </span></label>
                <div class="col-sm-8">
                    <input type="text" name="identity_number" class="form-control" id="idCard"
                        value="{{ old('identity_number') }}">
                    <div id="idCard-error" class="text-danger mt-2"></div>
                    @error('identity_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- <div class="form-group row">
                <label for="country" class="col-sm-4 col-form-label">Quốc gia <span style="color:red"> * </span></label>
                <div class="col-sm-8">
                    <select class="form-control" name="country" id="country">
                        <option value="">Chọn quốc gia</option>
                        @foreach ($quocgia as $item)
                        <option value="{{ $item['countryCode'] }}" {{ old('country')==$item['countryCode'] ? 'selected'
                            : '' }}>
                            {{ $item['countryName'] }}
                        </option>
                        @endforeach

                    </select>
                    @error('country')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div> --}}

            <div class="form-group row">
                <label for="province" class="col-sm-4 col-form-label">Tỉnh/Thành phố <span style="color:red"> *
                    </span></label>
                <div class="col-sm-8">
                    <select class="form-control" name="province" id="province">
                        <option value="">Chọn thành phố</option>
                        @foreach ($province as $item)
                        <option value="{{ $item->id }}" {{ old('country')==$item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('province')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="district" class="col-sm-4 col-form-label">Quận/Huyện <span style="color:red"> *
                    </span></label>
                <div class="col-sm-8">
                    <select class="form-control" id="district" name="district">
                        <option value="" {{ old('district')=='' ? 'selected' : '' }}>-- Vui lòng chọn --</option>
                        <!-- Thêm các quận/huyện khác nếu cần -->
                    </select>
                    @error('district')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="ward" class="col-sm-4 col-form-label">Xã phường <span style="color:red"> * </span></label>
                <div class="col-sm-8">
                    <select class="form-control" name="ward" id="ward">
                        <option value="" {{ old('ward')=='' ? 'selected' : '' }}>-- Vui lòng chọn --</option>
                        <!-- Thêm các xã/phường khác nếu cần -->
                    </select>
                    @error('ward')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-sm-4 col-form-label">Địa chỉ <span style="color:red"> * </span></label>
                <div class="col-sm-8">
                    <input type="text" name="address" class="form-control" id="address" value="{{ old('address') }}">
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="phone_number" class="col-sm-4 col-form-label">Số điện thoại <span style="color:red"> *
                    </span></label>
                <div class="col-sm-8">
                    <input type="tel" class="form-control" name="phone_number" id="phone_number"
                        value="{{ old('phone_number') }}">
                    <div id="phone-error" class="text-danger mt-2"></div>
                    @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">Email <span style="color:red"> * </span></label>
                <div class="col-sm-8">
                    <input type="text" name="email" class="form-control" id="email" value="{{ old('email') }}">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: flex; justify-content: center;">
                <div>
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: flex; justify-content: center;" class="mt-3">
                <button type="submit" id="submit-button" class="btn btn-primary px-5">Đăng ký</button>
            </div>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            // Khi chọn tỉnh thành
            $('#province').change(function() {
                var selectedProvince = $(this).val();

                if (selectedProvince) {
                    // Gửi yêu cầu AJAX để lấy các quận huyện
                    $.ajax({
                        url: '/get-districts',  // URL để lấy danh sách huyện
                        type: 'GET',
                        data: { province_id: selectedProvince },  // Gửi ID tỉnh thành
                        success: function(data) {
                            var districts = data.districts;
                            var districtSelect = $('#district');
                            districtSelect.empty();  // Xoá các mục cũ trong dropdown quận huyện
                            districtSelect.append('<option value="">Chọn quận huyện</option>');  // Thêm mục mặc định

                            // Thêm các huyện vào dropdown
                            districts.forEach(function(district) {
                                districtSelect.append('<option value="' + district.id + '">' + district.name + '</option>');
                            });

                            // Xóa danh sách xã khi thay đổi quận huyện
                            $('#ward').empty();
                            $('#ward').append('<option value="">Chọn phường xã</option>');
                        }
                    });
                } else {
                    // Nếu không có tỉnh thành được chọn, xoá quận huyện và xã
                    $('#district').empty();
                    $('#district').append('<option value="">Chọn quận huyện</option>');
                    $('#ward').empty();
                    $('#ward').append('<option value="">Chọn phường xã</option>');
                }
            });

            // Khi chọn quận huyện
            $('#district').change(function() {
                var selectedDistrict = $(this).val();

                if (selectedDistrict) {
                    // Gửi yêu cầu AJAX để lấy các xã
                    $.ajax({
                        url: '/get-wards',  // URL để lấy danh sách xã
                        type: 'GET',
                        data: { district_id: selectedDistrict },  // Gửi ID quận huyện
                        success: function(data) {
                            var wards = data.wards;
                            var wardSelect = $('#ward');
                            wardSelect.empty();  // Xoá các mục cũ trong dropdown xã
                            wardSelect.append('<option value="">Chọn phường xã</option>');  // Thêm mục mặc định

                            // Thêm các xã vào dropdown
                            wards.forEach(function(ward) {
                                wardSelect.append('<option value="' + ward.id + '">' + ward.name + '</option>');
                            });
                        }
                    });
                } else {
                    // Nếu không có quận huyện được chọn, xoá xã
                    $('#ward').empty();
                    $('#ward').append('<option value="">Chọn phường xã</option>');
                }
            });
        });
    </script>

<script>
    const usernameInput = document.getElementById('username');
    const phoneInput = document.getElementById('phone_number');
    const idCardInput = document.getElementById('idCard');
    const submitButton = document.getElementById('submit-button');
    const usernameError = document.getElementById('username-error');
    const phoneError = document.getElementById('phone-error');
    const idCardError = document.getElementById('idCard-error');

    function validateUsername(username) {
        return /^\S+$/.test(username);
    }

    function validatePhone(phone) {
        return /^\d{10}$/.test(phone);
    }

    function validateIdCard(idCard) {
        return /^\d{9}$|^\d{12}$/.test(idCard);
    }

    function validateField(input, errorElement, validateFunc, errorMessage) {
        if (!input.value.trim()) {
            errorElement.textContent = 'Trường này không được để trống.';
            input.classList.add('is-invalid');
            return false;
        }

        if (!validateFunc(input.value)) {
            errorElement.textContent = errorMessage;
            input.classList.add('is-invalid');
            return false;
        } else {
            errorElement.textContent = '';
            input.classList.remove('is-invalid');
            return true;
        }
    }
    usernameInput.addEventListener('input', function () {
        validateField(usernameInput, usernameError, validateUsername, 'Tên đăng nhập không được chứa khoảng trắng.');
    });

    phoneInput.addEventListener('input', function () {
        validateField(phoneInput, phoneError, validatePhone, 'Số điện thoại phải có 10 chữ số và không có chữ.');
    });

    idCardInput.addEventListener('input', function () {
        validateField(idCardInput, idCardError, validateIdCard, 'CMND/CCCD/Hộ chiếu phải gồm 9 hoặc 12 chữ số.');
    });

    function checkSubmitButton() {
        const isUsernameValid = validateUsername(usernameInput.value);
        const isPhoneValid = validatePhone(phoneInput.value);
        const isIdCardValid = validateIdCard(idCardInput.value);

        submitButton.disabled = !(isUsernameValid && isPhoneValid && isIdCardValid);
    }

    usernameInput.addEventListener('input', checkSubmitButton);
    phoneInput.addEventListener('input', checkSubmitButton);
    idCardInput.addEventListener('input', checkSubmitButton);
</script>
</body>

</html>
