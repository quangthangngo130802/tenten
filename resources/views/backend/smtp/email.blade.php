@extends('backend.layouts.master')

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active fw-bold" id="info-tab" href="">SMTP</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link  fw-bold" id="seo-tab" href="{{ route('smtp.template') }}">Email nhận thông báo</a>
        </li>

    </ul>



    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h5 class="card-title">Cấu hình SMTP</h5>
                        </div>

                        <form id="postForm" enctype="multipart/form-data" method="POST" action="{{ route('smtp.email.save') }}">
                            @csrf
                            <label for="email" class="form-label fw-bold">Email </label>
                            <div class=" mb-3">
                                <input type="text" class="form-control iconpicker-input" name="email" id="email"
                                    placeholder="Nhập để  email" value="{{ env('MAIL_USERNAME', '') }}">
                                <div class="error-message text-danger"></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Mật khẩu Email </label>
                                <input id="password" name="password" class="form-control" type="text"
                                    placeholder="Nhập mật khẩu"  value="{{ env('MAIL_PASSWORD', '') }}">
                                <div class="error-message text-danger"></div>
                            </div>

                            <div class="mb-3">
                                <label for="mail_name" class="form-label fw-bold">Tiêu đề gửi</label>
                                <input id="mail_name" name="mail_name" class="form-control" type="text"
                                    placeholder="Nhập Mail Name"  value="{{ env('APP_NAME1', '') }}">
                                <div class="error-message text-danger"></div>
                            </div>


                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary" id="save">Lưu</button>
                                <button type="button" id="cancelEdit" style="display: none"
                                    class="btn btn-secondary ms-2">Hủy</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("postForm");

            form.addEventListener("submit", function(event) {
                event.preventDefault();
                let isValid = true;


                const email = document.getElementById("email");
                const password = document.getElementById("password");
                const mailName = document.getElementById("mail_name");


                document.querySelectorAll(".error-message").forEach(div => div.textContent = "");


                if (email.value.trim() === "") {
                    email.nextElementSibling.textContent = "Email không được để trống!";
                    isValid = false;
                } else if (!validateEmail(email.value)) {
                    email.nextElementSibling.textContent = "Email không hợp lệ!";
                    isValid = false;
                }


                if (password.value.trim() === "") {
                    password.nextElementSibling.textContent = "Mật khẩu không được để trống!";
                    isValid = false;
                }


                if (mailName.value.trim() === "") {
                    mailName.nextElementSibling.textContent = "Tiêu đề gửi không được để trống!";
                    isValid = false;
                }

                if (isValid) {
                    console.log("Form hợp lệ! Gửi dữ liệu...");
                    form.submit(); // Bỏ dòng này nếu bạn muốn xử lý AJAX
                }
            });

            // Hàm kiểm tra định dạng email
            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
@endpush
