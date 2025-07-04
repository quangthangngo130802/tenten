<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <title>Đăng ký</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .registration-container {
            max-width: 800px !important;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .registration-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 2rem;
            border: none;
        }

        .card-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 2rem;
        }

        .card-header .subtitle {
            margin-top: 0.5rem;
            opacity: 0.9;
            font-size: 1rem;
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: #667eea;
            width: 16px;
        }

        .required {
            color: #e74c3c;
        }

        .input-group {
            position: relative;
        }

        .form-control {
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: #fff;
        }

        .form-control.is-invalid {
            border-color: #e74c3c;
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        }

        .invalid-feedback {
            display: block !important;
            background: #ffeaea;
            border: 1px solid #e74c3c;
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        .text-danger {
            background: #ffeaea;
            border: 1px solid #e74c3c;
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-register:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .recaptcha-container {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }

        .submit-container {
            text-align: center;
            margin-top: 2rem;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e8ed;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #764ba2;
            text-decoration: none;
        }

        /* Animation for form fields */
        .form-group {
            animation: slideInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }

        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .registration-container {
                margin: 1rem auto;
                padding: 0 0.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-header {
                padding: 1.5rem;
            }

            .card-header h2 {
                font-size: 1.5rem;
            }
        }

        .g-recaptcha {
            max-width: 100%;
        }

        /* Icon styling */
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            z-index: 10;
        }

        .form-control.with-icon {
            padding-left: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="container registration-container">
        <div class="card registration-card">
            <div class="card-header">
                <h2><i class="fas fa-user-plus"></i> Đăng ký tài khoản</h2>
                <p class="subtitle">Tạo tài khoản mới để bắt đầu sử dụng dịch vụ</p>
            </div>

            <div class="card-body">
                <form action="{{ route('submit.register') }}" method="post">
                    @csrf

                    <!-- Username Field -->
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-user"></i>
                            Tên đăng nhập <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="username" class="form-control with-icon" id="username"
                                   value="{{ old('username') }}" placeholder="Nhập tên đăng nhập">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        <div id="username-error" class="text-danger mt-2"></div>
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Mật khẩu <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control with-icon" id="password"
                                   placeholder="Nhập mật khẩu">
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Full Name Field -->
                    <div class="form-group">
                        <label for="full_name" class="form-label">
                            <i class="fas fa-id-card"></i>
                            Họ và tên <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="full_name" class="form-control with-icon" id="full_name"
                                   value="{{ old('full_name') }}" placeholder="Nhập họ và tên">
                            <i class="fas fa-id-card input-icon"></i>
                        </div>
                        @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group">
                        <label for="phone_number" class="form-label">
                            <i class="fas fa-phone"></i>
                            Số điện thoại <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="tel" class="form-control with-icon" name="phone_number" id="phone_number"
                                   value="{{ old('phone_number') }}" placeholder="Nhập số điện thoại">
                            <i class="fas fa-phone input-icon"></i>
                        </div>
                        <div id="phone-error" class="text-danger mt-2"></div>
                        @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email <span class="required">*</span>
                        </label>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control with-icon" id="email"
                                   value="{{ old('email') }}" placeholder="Nhập địa chỉ email">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="recaptcha-container">
                        <div>
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="submit-container">
                        <button type="submit" id="submit-button" class="btn btn-register px-5">
                            <i class="fas fa-user-plus me-2"></i>
                            Đăng ký tài khoản
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="login-link">
                        <p>Đã có tài khoản? <a href="#">Đăng nhập ngay</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // Province/District/Ward AJAX logic (keeping your original code)
            $('#province').change(function() {
                var selectedProvince = $(this).val();
                if (selectedProvince) {
                    $.ajax({
                        url: '/get-districts',
                        type: 'GET',
                        data: { province_id: selectedProvince },
                        success: function(data) {
                            var districts = data.districts;
                            var districtSelect = $('#district');
                            districtSelect.empty();
                            districtSelect.append('<option value="">Chọn quận huyện</option>');
                            districts.forEach(function(district) {
                                districtSelect.append('<option value="' + district.id + '">' + district.name + '</option>');
                            });
                            $('#ward').empty();
                            $('#ward').append('<option value="">Chọn phường xã</option>');
                        }
                    });
                } else {
                    $('#district').empty();
                    $('#district').append('<option value="">Chọn quận huyện</option>');
                    $('#ward').empty();
                    $('#ward').append('<option value="">Chọn phường xã</option>');
                }
            });

            $('#district').change(function() {
                var selectedDistrict = $(this).val();
                if (selectedDistrict) {
                    $.ajax({
                        url: '/get-wards',
                        type: 'GET',
                        data: { district_id: selectedDistrict },
                        success: function(data) {
                            var wards = data.wards;
                            var wardSelect = $('#ward');
                            wardSelect.empty();
                            wardSelect.append('<option value="">Chọn phường xã</option>');
                            wards.forEach(function(ward) {
                                wardSelect.append('<option value="' + ward.id + '">' + ward.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#ward').empty();
                    $('#ward').append('<option value="">Chọn phường xã</option>');
                }
            });
        });

        // Validation logic (keeping your original validation)
        const usernameInput = document.getElementById('username');
        const phoneInput = document.getElementById('phone_number');
        const submitButton = document.getElementById('submit-button');
        const usernameError = document.getElementById('username-error');
        const phoneError = document.getElementById('phone-error');

        function validateUsername(username) {
            return /^\S+$/.test(username);
        }

        function validatePhone(phone) {
            return /^\d{10}$/.test(phone);
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
            checkSubmitButton();
        });

        phoneInput.addEventListener('input', function () {
            validateField(phoneInput, phoneError, validatePhone, 'Số điện thoại phải có 10 chữ số và không có chữ.');
            checkSubmitButton();
        });

        function checkSubmitButton() {
            const isUsernameValid = usernameInput.value.trim() && validateUsername(usernameInput.value);
            const isPhoneValid = phoneInput.value.trim() && validatePhone(phoneInput.value);
            const isFullNameValid = document.getElementById('full_name').value.trim();
            const isEmailValid = document.getElementById('email').value.trim();
            const isPasswordValid = document.getElementById('password').value.trim();

            submitButton.disabled = !(isUsernameValid && isPhoneValid && isFullNameValid && isEmailValid && isPasswordValid);
        }

        // Check submit button on all required fields
        document.getElementById('full_name').addEventListener('input', checkSubmitButton);
        document.getElementById('email').addEventListener('input', checkSubmitButton);
        document.getElementById('password').addEventListener('input', checkSubmitButton);

        // Initial check
        checkSubmitButton();
    </script>
</body>
</html>
