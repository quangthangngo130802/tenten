@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Số tiền</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th class="text-center">Trạng thái</th>
                    </tr>
                </thead>
            </table>
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
                    data: 'user_info',
                    name: 'user_info',
                    orderable: false,
                },
                {
                    data: 'amount',
                    name: 'amount',
                    orderable: false,
                },
                {
                    data: 'description',
                    name: 'description',
                    orderable: false,
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    orderable: false,
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    orderable: false,
                },
            ];


            $('#categoryTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/zalo/transaction',
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

            $(document).on('click', '.btn-action', function(e) {
                e.preventDefault();

                let id = $(this).data('id');
                let status = $(this).data('status');
                let actionText = status === 0 ? 'Xác nhận' : 'Từ chối';

                Swal.fire({
                    title: `Bạn có chắc muốn ${actionText.toLowerCase()}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: `Có, ${actionText}`,
                    cancelButtonText: 'Hủy',
                    confirmButtonColor: status === 0 ? '#28a745' : '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/zalo/update-status',
                            method: 'POST',
                            data: {
                                id: id,
                                status: status,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                $('#categoryTable').DataTable().ajax.reload(null, false);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: 'Có lỗi xảy ra, vui lòng thử lại.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });


        });
    </script>
@endpush
