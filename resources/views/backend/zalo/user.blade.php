@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="fa fa-plus"></i> Thêm khách hàng
        </button>
        <div class="category-list">
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tiền tố</th>
                        <th>Họ tên</th>
                        <th>Điện thoại</th>
                        <th>Email</th>
                        <th>Ví chính</th>
                        <th>Ví phụ</th>
                        <th>Hoạt động</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="modal fade" id="viewUserModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thông tin khách hàng</h5>
                    </div>
                    <div class="modal-body">
                        <p><strong>Tiền tố:</strong> <span id="view-prefix"></span></p>
                        <p><strong>Họ tên:</strong> <span id="view-name"></span></p>
                        <p><strong>Điện thoại:</strong> <span id="view-phone"></span></p>
                        <p><strong>Email:</strong> <span id="view-email"></span></p>
                        <p><strong>Username:</strong> <span id="view-username"></span></p>
                        <p><strong>Địa chỉ:</strong> <span id="view-address"></span></p>
                        <p><strong>Công ty:</strong> <span id="view-company"></span></p>
                        <p><strong>Mã số thuế:</strong> <span id="view-taxcode"></span></p>
                        <p><strong>Cửa hàng:</strong> <span id="view-store"></span></p>
                        <p><strong>Lĩnh vực:</strong> <span id="view-field"></span></p>
                        <p><strong>Ví chính:</strong> <span id="view-wallet"></span></p>
                        <p><strong>Ví phụ:</strong> <span id="view-subwallet"></span></p>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editUserModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="editUserForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sửa thông tin khách hàng</h5>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit-id" name="id">
                            <input type="text" class="form-control mb-2" name="prefix" id="edit-prefix"
                                placeholder="Tiền tố">
                            <input type="text" class="form-control mb-2" name="name" id="edit-name"
                                placeholder="Họ tên">
                            <input type="text" class="form-control mb-2" name="phone" id="edit-phone"
                                placeholder="Số điện thoại">
                            <input type="email" class="form-control mb-2" name="email" id="edit-email"
                                placeholder="Email">
                            <input type="text" class="form-control mb-2" name="username" id="edit-username"
                                placeholder="Tên đăng nhập">
                            <input type="text" class="form-control mb-2" name="address" id="edit-address"
                                placeholder="Địa chỉ">
                            <input type="text" class="form-control mb-2" name="company_name" id="edit-company"
                                placeholder="Tên công ty">
                            <input type="text" class="form-control mb-2" name="tax_code" id="edit-taxcode"
                                placeholder="Mã số thuế">
                            <input type="text" class="form-control mb-2" name="store_name" id="edit-store"
                                placeholder="Tên cửa hàng">
                            <input type="text" class="form-control mb-2" name="field" id="edit-field"
                                placeholder="Lĩnh vực">


                            <input type="text" class="form-control mb-2 currency-input" name="wallet" id="edit-wallet"
                                placeholder="Ví chính">
                            <input type="text" class="form-control mb-2 currency-input" name="sub_wallet"
                                id="edit-subwallet" placeholder="Ví phụ">


                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="deleteUserModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="deleteUserForm">
                    @csrf
                    <input type="hidden" name="id" id="delete-id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xác nhận xoá</h5>
                        </div>
                        <div class="modal-body">Bạn có chắc chắn xoá <strong id="delete-name"></strong>?</div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Xoá</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="createUserModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="createUserForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm khách hàng mới</h5>
                        </div>
                        <div class="modal-body">
                            <input type="text" class="form-control mb-2" name="prefix" placeholder="Tiền tố"
                                required>
                            <input type="text" class="form-control mb-2" name="name" placeholder="Họ tên"
                                required>
                            <input type="text" class="form-control mb-2" name="phone" placeholder="Số điện thoại"
                                required>
                            <input type="email" class="form-control mb-2" name="email" placeholder="Email" required>
                            <input type="text" class="form-control mb-2" name="username" id="edit-username"
                                placeholder="Tên đăng nhập">
                            <input type="password" class="form-control mb-2" name="password" id="edit-password"
                                placeholder="Mật khẩu">
                            <input type="text" class="form-control mb-2" name="address" placeholder="Địa chỉ"
                                required>
                            <input type="text" class="form-control mb-2" name="company_name"
                                placeholder="Tên công ty">
                            <input type="text" class="form-control mb-2" name="tax_code" placeholder="Mã số thuế">
                            <input type="text" class="form-control mb-2" name="store_name"
                                placeholder="Tên cửa hàng">
                            <input type="text" class="form-control mb-2" name="field" placeholder="Lĩnh vực">
                            <input type="text" class="form-control mb-2 currency-input" name="wallet"
                                placeholder="Ví chính">
                            <input type="text" class="form-control mb-2 currency-input" name="sub_wallet"
                                placeholder="Ví phụ">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



    </div>
@endsection

@push('styles')
    <style>
        th.no-sort::after,
        th.no-sort::before {
            display: none !important;
        }

        .dt-column-order {
            display: none !important;
        }

        .dt-column-title {
            font-size: 11px !important;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var APP_URL = '{{ env('APP_URL') }}';

            var columns = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'prefix',
                    name: 'prefix',
                    orderable: false,
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: false,
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: false,
                },
                {
                    data: 'email',
                    name: 'email',
                    orderable: false,
                },
                {
                    data: 'wallet',
                    name: 'wallet',
                    orderable: false,
                },
                {
                    data: 'sub_wallet',
                    name: 'sub_wallet',
                    orderable: false,
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                },
            ];

            // Khởi tạo DataTable
            $('#categoryTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/zalo/user',
                columns: columns,
                columnDefs: [{
                    targets: 0,
                    orderable: false,
                    className: 'no-sort'
                }],
                pagingType: "full_numbers",
                // scrollX: true,
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

            $(document).on('click', '.view-user', function() {
                $('#view-prefix').text($(this).data('prefix'));
                $('#view-name').text($(this).data('name'));
                $('#view-phone').text($(this).data('phone'));
                $('#view-email').text($(this).data('email'));
                $('#view-username').text($(this).data('username'));
                $('#view-address').text($(this).data('address'));
                $('#view-company').text($(this).data('company_name'));
                $('#view-taxcode').text($(this).data('tax_code'));
                $('#view-store').text($(this).data('store_name'));
                $('#view-field').text($(this).data('field'));
                $('#view-wallet').text($(this).data('wallet'));
                $('#view-subwallet').text($(this).data('sub_wallet'));
                // $('#view-created').text($(this).data('created_at'));
                // $('#view-expired').text($(this).data('expired_at'));
                $('#viewUserModal').modal('show');
            });

            // Hiển thị modal sửa
            $(document).on('click', '.edit-user', function() {
                $('#edit-id').val($(this).data('id'));
                $('#edit-prefix').val($(this).data('prefix'));
                $('#edit-name').val($(this).data('name'));
                $('#edit-phone').val($(this).data('phone'));
                $('#edit-email').val($(this).data('email'));
                $('#edit-username').val($(this).data('username'));
                $('#edit-address').val($(this).data('address'));
                $('#edit-company').val($(this).data('company_name'));
                $('#edit-taxcode').val($(this).data('tax_code'));
                $('#edit-store').val($(this).data('store_name'));
                $('#edit-field').val($(this).data('field'));
                $('#edit-wallet').val($(this).data('wallet'));
                $('#edit-subwallet').val($(this).data('sub_wallet'));
                // $('#edit-expired').val($(this).data('expired_at'));
                $('#editUserModal').modal('show');
            });

            // Submit form sửa
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: APP_URL + '/admin/zalo/user/update',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        $('#editUserModal').modal('hide');
                        $('#categoryTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Cập nhật thành công!',
                            showConfirmButton: false,
                            timer: 1500
                        });

                    },
                    error: function() {
                        alert('Lỗi cập nhật!');
                    }
                });
            });

            $(document).on('click', '.delete-user', function() {
                let userId = $(this).data('id');
                let userName = $(this).data('name');

                Swal.fire({
                    title: 'Xác nhận xoá',
                    text: `Bạn có chắc muốn xoá khách hàng "${userName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xoá',
                    cancelButtonText: 'Huỷ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('{{ route('zalo.user.delete') }}', {
                            _token: '{{ csrf_token() }}',
                            id: userId
                        }).done(function() {
                            $('#categoryTable').DataTable().ajax.reload();

                            Swal.fire({
                                icon: 'success',
                                title: 'Đã xoá',
                                text: `Khách hàng "${userName}" đã bị xoá!`,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }).fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Không thể xoá khách hàng!'
                            });
                        });
                    }
                });
            });

            // Submit form thêm mới
            $('#createUserForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: APP_URL + '/admin/zalo/user/add',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function() {
                        $('#createUserModal').modal('hide');
                        $('#createUserForm')[0].reset();
                        $('#categoryTable').DataTable().ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Thêm thành công',
                            text: 'Khách hàng đã được thêm!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Không thể thêm khách hàng!'
                        });
                    }
                });
            });


        });
    </script>

    <script>
        function formatCurrencyInput(input) {
            let value = input.value.replace(/\D/g, '');

            value = value.replace(/^0+/, '');
            if (!value) {
                input.value = '';
                return;
            }

            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        $(document).on('input', '.currency-input', function() {
            formatCurrencyInput(this);
        });
    </script>
@endpush
