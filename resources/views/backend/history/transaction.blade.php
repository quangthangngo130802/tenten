@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>STT</th>

                        <th>Khách hàng </th>

                        <th>Tổng tiền</th>
                        <th>Nội dung</th>
                        <th>Ngày tạo</th>
                        <th>Hoạt động</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Xét duyệt giao dịch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="transaction_id" id="transaction_id">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="status" class="form-control" required>
                                <option value="">-- Chọn --</option>
                                <option value="approved">Duyệt</option>
                                <option value="rejected">Không duyệt</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Xác nhận</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        td,
        th {
            text-align: center !important;
        }

        .dataTables_scrollBody thead tr {
            display: none;
        }

        #add-category-btn {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            */
            /* text-align: end; */
            padding: 10px;
            margin-right: 100px;
        }


        td a {
            padding: 8px 11px !important;
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
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var APP_URL = '{{ env('APP_URL') }}';

            // Khởi tạo mảng cột
            var columns = [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'approved_at',
                    name: 'approved_at'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ];

            // Cấu hình columnDefs để vô hiệu hóa sắp xếp các cột mong muốn
            var columnDefs = [{
                    targets: 0,
                    width: '5%',
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 1,
                    width: '25%',
                    orderable: false
                },
                {
                    targets: 2,
                    width: '15%',
                    orderable: false
                },
                {
                    targets: 3,
                    width: '20%',
                    orderable: false
                },
                {
                    targets: 4,
                    width: '15%'
                }, // Cho phép sắp xếp cột ngày duyệt
                {
                    targets: 5,
                    orderable: false,
                    searchable: false
                } // Action column
            ];

            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/transaction',
                columns: columns,
                columnDefs: columnDefs,
                order: [
                    [4, 'desc']
                ], // Cột ngày duyệt là index 4
                pagingType: "full_numbers",

                language: {
                    paginate: {
                        previous: '&laquo;',
                        next: '&raquo;'
                    },
                    lengthMenu: "Hiển thị _MENU_ mục mỗi trang",
                    zeroRecords: "Không tìm thấy dữ liệu",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    infoEmpty: "Không có dữ liệu để hiển thị",
                    infoFiltered: "(lọc từ _MAX_ mục)"
                },
            });

            window.openApproveModal = function(id) {
                $('#transaction_id').val(id);
                $('#approveForm').attr('action', '/transaction/approve/' + id);
                $('#approveModal').modal('show');
            };

            $('#approveForm').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {

                        $('#approveModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: 'Cập nhật trạng thái thành công!',
                            timer: 1500,
                            showConfirmButton: false
                        });


                        $('#categoryTable').DataTable().ajax.reload(null,
                        false);
                    },
                    error: function(xhr) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: xhr.responseJSON?.message ||
                                'Đã xảy ra lỗi khi cập nhật.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
