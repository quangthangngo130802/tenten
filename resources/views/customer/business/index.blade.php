@extends('backend.layouts.master')

@section('content')
    <div class="container my-4">
        <!-- Tiêu đề -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            {{-- <a href="#" class="text-decoration-none">&larr; Chọn cách xác thực OA</a> --}}
            <h1 class="content-title flex-grow-1 text-center m-0">Xác thực theo tên đăng ký kinh doanh</h1>
            {{-- <a href="#" class="text-decoration-none">Thoát xác thực OA &times;</a> --}}
        </div>
        {{-- <form method="post" enctype="multipart/form-data" action="{{ route('business.registration.submit') }}">
            @csrf
            <!-- Nội dung -->
            <div class="content-box">
                <div class="row">
                    <!-- Hướng dẫn -->
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h5>Hướng dẫn</h5>
                        <ul>
                            <li>Tài liệu tải lên phải là ảnh chụp/scan từ bản gốc. Không chấp nhận các tài liệu photo, kể cả
                                bản
                                công chứng.</li>
                            <li>Tài liệu cần rõ ràng, không bị mờ.</li>
                            <li>Định dạng JPG, PNG, PDF. Dung lượng tập tin tối đa 5MB.</li>
                            <li>
                                Công văn cần điền đầy đủ tất cả thông tin yêu cầu
                                (Tên doanh nghiệp, OA ID, có chữ ký tay đi kèm dấu mộc của doanh nghiệp, hoặc chữ ký số).
                                Doanh nghiệp được đề xuất sử dụng công văn có chữ ký số.
                            </li>
                        </ul>
                    </div>

                    <!-- Tài liệu yêu cầu -->
                    <div class="col-md-6">
                        <h5>Tài liệu yêu cầu</h5>
                        <p><strong>1. Công văn yêu cầu mở tài khoản</strong></p>
                        <p class="mb-2">Tối đa 1 tài liệu. <a href="#">Tải mẫu tại đây.</a></p>

                        <!-- Upload button -->
                        <label class="btn-upload" id="uploadLabel">
                            <i class="fa-solid fa-upload" style="margin-right: 10px"></i> Chọn tài liệu
                            <input type="file" name="fileInput" id="fileInput" style="display: none;">
                        </label>

                        <div id="filePreview" class="file-preview mt-3 d-none">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF Icon"
                                        class="me-2">
                                    <div>
                                        <div><strong id="fileName">Tên file</strong></div>
                                        <div class="text-muted" id="fileDescription">[OA IT] PHP_Intern.pdf</div>
                                    </div>
                                </div>
                                <button type="button" id="removeFile" class="btn">
                                    <i class="fas fa-times"></i> <!-- icon Font Awesome (version 5) -->
                                </button>
                            </div>
                        </div>

                        <button id="continueBtn" type="button" class="btn btn-primary continue-btn mt-2"
                            data-bs-toggle="modal" data-bs-target="#businessModal">
                            Tiếp tục
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="businessModal" tabindex="-1" aria-labelledby="businessModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-lg-custom">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="businessModalLabel">Thông tin doanh nghiệp</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- Form bên trái -->
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="businessCode" class="form-label">
                                            Mã số doanh nghiệp <small style="color: red;">*</small>
                                        </label>
                                        <input type="text" class="form-control" id="businessCode" name="businessCode"
                                            placeholder="Nhập mã số doanh nghiệp">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="businessName" class="form-label">
                                            Tên doanh nghiệp <small style="color: red;">*</small>
                                        </label>
                                        <input type="text" class="form-control" id="businessName" name="businessName"
                                            placeholder="Nhập tên doanh nghiệp">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="businessAddress" class="form-label">
                                            Địa chỉ doanh nghiệp <small style="color: red;">*</small>
                                        </label>
                                        <input type="text" class="form-control" id="businessAddress"
                                            name="businessAddress" placeholder="Nhập địa chỉ doanh nghiệp">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="representative" class="form-label">
                                            Tên người đại diện <small style="color: red;">*</small>
                                        </label>
                                        <input type="text" class="form-control" id="representative" name="representative"
                                            placeholder="Nhập tên người đại diện">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="contactPhone" class="form-label">
                                            Số điện thoại liên hệ
                                        </label>
                                        <input type="text" class="form-control" id="contactPhone" name="contactPhone"
                                            placeholder="Nhập số điện thoại">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="contactEmail" class="form-label">
                                            Email liên hệ <small style="color: red;">*</small>
                                        </label>
                                        <input type="email" class="form-control" id="contactEmail" name="contactEmail"
                                            placeholder="Nhập email liên hệ">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                </div>


                                <!-- Ảnh hoặc iframe bên phải -->
                                <div class="col-md-6">
                                    <ul class="nav nav-tabs" id="imageTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pdf-tab" data-bs-toggle="tab"
                                                data-bs-target="#pdf" type="button" role="tab">View</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-2" id="imageTabContent">
                                        <div class="tab-pane fade show active" id="pdf" role="tabpanel">
                                            <div class="img-frame">
                                                <iframe id="fileIframe" src="" width="100%"
                                                    height="500px"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Xác thực ngay</button>
                        </div>
                    </div>
                </div>
            </div>
        </form> --}}

        <div class="card my-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin doanh nghiệp</h5>
            </div>
            <div class="card-body">
                <form id="businessForm"method="post" enctype="multipart/form-data"
                    action="{{ route('business.registration.submit') }}">
                    @csrf
                    <div class="row">
                        <!-- Form bên trái -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="businessCode" class="form-label">
                                    Mã số doanh nghiệp <small style="color: red;">*</small>
                                </label>
                                <input type="text" class="form-control" id="businessCode" name="businessCode"
                                    value="{{ old('businessCode', isset($business) ? $business->businessCode : '') }}"
                                    placeholder="Nhập mã số doanh nghiệp">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="businessName" class="form-label">
                                    Tên doanh nghiệp <small style="color: red;">*</small>
                                </label>
                                <input type="text" class="form-control" id="businessName" name="businessName"
                                    value="{{ old('businessName', isset($business) ? $business->businessName : '') }}"
                                    placeholder="Nhập tên doanh nghiệp">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="businessAddress" class="form-label">
                                    Địa chỉ doanh nghiệp <small style="color: red;">*</small>
                                </label>
                                <input type="text" class="form-control" id="businessAddress" name="businessAddress"
                                    value="{{ old('businessAddress', isset($business) ? $business->businessAddress : '') }}"
                                    placeholder="Nhập địa chỉ doanh nghiệp">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="representative" class="form-label">
                                    Tên người đại diện <small style="color: red;">*</small>
                                </label>
                                <input type="text" class="form-control" id="representative" name="representative"
                                    value="{{ old('representative', isset($business) ? $business->representative : '') }}"
                                    placeholder="Nhập tên người đại diện">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="contactPhone" class="form-label">
                                    Số điện thoại liên hệ
                                </label>
                                <input type="text" class="form-control" id="contactPhone" name="contactPhone"
                                    value="{{ old('contactPhone', isset($business) ? $business->contactPhone : '') }}"
                                    placeholder="Nhập số điện thoại">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="contactEmail" class="form-label">
                                    Email liên hệ <small style="color: red;">*</small>
                                </label>
                                <input type="email" class="form-control" id="contactEmail" name="contactEmail"
                                    value="{{ old('contactEmail', isset($business) ? $business->contactEmail : '') }}"
                                    placeholder="Nhập email liên hệ">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>


                        <!-- Ảnh hoặc iframe bên phải -->
                        <div class="col-md-6">
                            <ul class="nav nav-tabs" id="imageTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pdf-tab" data-bs-toggle="tab"
                                        data-bs-target="#pdf" type="button" role="tab">View</button>
                                </li>
                                <label class="btn-upload mx-5" id="uploadLabel">
                                    <i class="fa-solid fa-upload" style="margin-right: 10px"></i> Chọn tài liệu
                                    <input type="file" name="fileInput" id="fileInput" style="display: none;">
                                </label>
                            </ul>
                            <div class="tab-content mt-2" id="imageTabContent">
                                <div class="tab-pane fade show active" id="pdf" role="tabpanel">
                                    <div class="img-frame">
                                        <iframe id="fileIframe"
                                            src="{{ old('businessCode', isset($business) ?asset($business->file_path) : '') }}"
                                            width="100%" height="500px"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" id="submitBtn" class="btn btn-primary">Xác thực ngay</button>
                    </div>
                </form>
            </div>
        </div>

    </div>


    <!-- Modal -->
@endsection
@push('scripts')
    <script>
        document.getElementById('businessForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const requiredFields = [
                'businessCode',
                'businessName',
                'businessAddress',
                'representative',
                'contactEmail'
            ];

            // Xóa lỗi cũ
            requiredFields.forEach(id => {
                const input = document.getElementById(id);
                input.classList.remove('is-invalid');
                input.nextElementSibling.textContent = '';
            });

            // Kiểm tra lần lượt từng trường
            for (let id of requiredFields) {
                const input = document.getElementById(id);
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    input.nextElementSibling.textContent = 'Trường này không được để trống.';
                    input.focus();
                    return; // Dừng kiểm tra, báo lỗi trường này trước
                }
                if (id === 'contactEmail') {
                    if (!validateEmail(input.value.trim())) {
                        input.classList.add('is-invalid');
                        input.nextElementSibling.textContent = 'Email không hợp lệ.';
                        input.focus();
                        return; // Dừng kiểm tra, báo lỗi email
                    }
                }
            }

            // Nếu đến đây là hợp lệ
            // if (isValid) {
            // alert('Thông tin hợp lệ, chuẩn bị gửi dữ liệu...');
            e.target.submit(); // Gửi form luôn
            // }
        });

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>


    <script>
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileDescription = document.getElementById('fileDescription');
        const removeFile = document.getElementById('removeFile');
        const continueBtn = document.getElementById('continueBtn');
        const uploadLabel = document.getElementById('uploadLabel');
        const fileIframe = document.getElementById('fileIframe');

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                // fileName.textContent = file.name;
                // fileDescription.textContent = `[OA IT] ${file.name}`;
                // filePreview.classList.remove('d-none');
                // continueBtn.style.display = 'inline-block';
                // uploadLabel.style.display = 'none';

                const fileURL = URL.createObjectURL(file);
                const fileType = file.type;

                if (fileType === 'application/pdf' || fileType.startsWith('image/')) {
                    fileIframe.src = fileURL;
                } else if (
                    file.name.endsWith('.docx') ||
                    fileType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ) {
                    fileIframe.src = '';
                    alert('Trình duyệt không hỗ trợ xem trực tiếp file DOCX. Vui lòng tải xuống để xem.');
                } else {
                    fileIframe.src = '';
                    alert('Định dạng file này không được hỗ trợ để xem trực tiếp.');
                }
            }
        });

        // removeFile.addEventListener('click', () => {
        //     fileInput.value = '';
        //     filePreview.classList.add('d-none');
        //     continueBtn.style.display = 'none';
        //     uploadLabel.style.display = 'inline-block';
        //     fileIframe.src = '';
        // });
    </script>
@endpush

@push('styles')
    <style>
        .content-box {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .content-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            text-align: center;
        }

        .file-preview {
            background: #f1f3f5;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
        }

        .file-preview img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .file-preview .btn-close {
            background: none;
            border: none;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn-upload {
            background: #f1f3f5;
            border: 1px solid #ced4da;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-upload:hover {
            background: #e2e6ea;
        }

        .continue-btn {
            display: none;
        }

        .btn-upload {
            background-color: #e7e7e7;
            border: 1px dashed #ccc;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .btn-upload:hover {
            background-color: #d9d9d9;
        }

        .modal-lg-custom {
            max-width: 75%;
        }

        .img-frame {
            /* width: 100%; */
            height: 500px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .img-frame iframe,
        .img-frame img {
            /* max-height: 100%; */
            max-width: 100%;
        }
    </style>
@endpush
