@extends('backend.layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="qrForm" action="{{ route('qrcode.save') }}" method="POST">
                @csrf
                <input type="hidden" name="qr_id" id="qr_id" value="">
                <h5 class="section-title" id="form-title">Tạo QR Code 1</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="qr_name" class="form-label">Tên QR</label>
                            <input type="text" class="form-control @error('qr_name') is-invalid @enderror" id="qr_name"
                                name="qr_name" placeholder="Nhập tên QR" value="{{ old('qr_name') }}" />
                            @error('qr_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="qr_link" class="form-label">Link QR</label>
                            <input type="url" class="form-control @error('qr_link') is-invalid @enderror" id="qr_link"
                                name="qr_link" placeholder="Nhập link QR" required value="{{ old('qr_link') }}" />
                            @error('qr_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-sm" id="submit-btn">
                                Save
                            </button>

                            <!-- Nút mở popup -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#uploadModal">
                                Upload File
                            </button>

                            <!-- Modal chọn file -->
                            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form id="uploadForm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="uploadModalLabel">Chọn file để tải lên</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Đóng"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="file" name="file" id="fileInput"
                                                    accept="image/*,.pdf,.doc,.docx" class="form-control">
                                                <div id="file-name" class="mt-2 text-muted"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Tải lên</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div id="qrcode" class="p-3" style="min-width: 250px; min-height: 250px;">
                            <img id="qrImage" src="" alt="">
                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>
    <div class="content">
        <div style="overflow-x: auto;">
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th class="text-center">Tên Qr Code</th>
                        <th class="text-center">Link</th>
                        <th class="text-center">Ảnh</th>
                        <th class="text-center" style="width: 200px;">Lượt quét</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    </div>
@endsection

@push('styles')
    <style>
        td a {
            /* padding: 8px 11px !important; */
            border-radius: 5px;
            color: white;
            display: inline-block;
        }

        .edit {
            background: #ffc107;
            margin: 0px 15px;
        }

        .delete {
            background: #dc3545;
            padding: 8px 12px !important;
        }

        td,
        th {
            text-align: center !important;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var APP_URL = '{{ env('APP_URL') }}';
            var typeId = '{{ request()->type_id }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/qrcode',
                order: [], // Vô hiệu hóa sắp xếp mặc định
                columns: [{
                        data: null, // Chúng ta sẽ thêm số thứ tự thủ công
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Lấy chỉ số hàng +1 để hiển thị số thứ tự
                        }
                    },
                    {
                        data: 'qr_name',
                        name: 'qr_name',
                        orderable: false
                    },
                    {
                        data: 'qr_link',
                        name: 'qr_link',
                        orderable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'scan_count',
                        name: 'scan_count',
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        width: '5%',
                        targets: 0
                    },
                    {
                        width: '25%',
                        targets: 1
                    },
                    {
                        width: '25%',
                        targets: 2
                    },
                    {
                        width: '12%',
                        targets: 3
                    },
                    {
                        width: '12%',
                        targets: 4
                    },
                ],
                pagingType: "full_numbers", // Kiểu phân trang
                language: {
                    paginate: {
                        previous: '&laquo;', // Nút trước
                        next: '&raquo;' // Nút sau
                    },
                    lengthMenu: "Hiển thị _MENU_ mục mỗi trang",
                    zeroRecords: "Không tìm thấy dữ liệu",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    infoEmpty: "Không có dữ liệu để hiển thị",
                    infoFiltered: "(lọc từ _MAX_ mục)"
                },
            });

            $("#qr_link").on("input", function() {
                // alert(1);
                let qrName = $("#qr_name").val();
                let qrLink = $(this).val();

                if (qrLink.length > 0) {
                    $.ajax({
                        url: "{{ route('qrcode.imageurl') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            qr_name: qrName,
                            qr_link: qrLink
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#qrcode img").attr("src", response.qr_code_url).show();
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    $("#qrcode img").hide();
                }
            });

            $(document).on('click', '.edit', function() {
                let id = $(this).data('id');
                let qrName = $(this).data('qr_name');
                let qrLink = $(this).data('qr_link');
                let defaultLink = $(this).data('default_link');

                $('#qr_id').val(id);
                $('#qr_name').val(qrName);
                $('#qr_link').val(qrLink);

                let actionUrl = "{{ route('qrcode.save', ':id') }}";
                actionUrl = actionUrl.replace(':id', id);
                $('#qrForm').attr('action', actionUrl);

                $('#form-title').text('Cập nhật QR Code');
                $('#submit-btn').text('Cập nhật');

                // Hiển thị ảnh QR nếu có
                $('#qrImage').attr('src', defaultLink);
            });
        });

        function confirmDelete(event, id) {
            event.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const qrImage = document.getElementById("qrImage");

            if (qrImage) {
                qrImage.addEventListener("click", function() {
                    fetch(qrImage.src)
                        .then(response => response.blob())
                        .then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement("a");
                            a.href = url;
                            a.download = "qr_code.png"; // Tên file khi tải về
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            window.URL.revokeObjectURL(url);
                        })
                        .catch(error => console.error("Lỗi tải ảnh:", error));
                });
            }
        });
    </script>
@endpush
