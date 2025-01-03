@extends('backend.layouts.master')

@section('content')
<div class="content">
    <!-- Bảng danh sách danh mục -->
    <div class="category-list">
        <table class="table table-striped table-hover" id="categoryTable">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    {{-- <th>Chi tiết</th> --}}
                    <th>Tổng tiền</th>
                    <th>Trạng thái thanh toán</th>
                    <th>Ngày đặt hàng</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
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

    td, th{
        text-align: center;
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(document).ready(function() {
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('customer.order.index', ['status' => request()->status]) }}',
                columns: [
                    {
                        data: 'code',
                        name: 'code'
                    },
                    // {
                    //     data: 'detail',
                    //     name: 'id'
                    // },
                    {
                        data: 'amount',
                        name: 'amount'
                    },

                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'payment',
                        name: 'payment',
                        orderable: false,
                        searchable: false
                    },

                ],
                columnDefs: [{
                        width: '19%',
                        targets: 0
                    },
                    {
                        width: '15%',
                        targets: 1
                    },
                    {
                        width: '20%',
                        targets: 2
                    },
                    {
                        width: '20%',
                        targets: 3
                    },

                    {
                        width: '15%',
                        targets: 4
                    },
                    // {
                    //     width: '15%',
                    //     targets: 5
                    // },


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
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>t<"row"<"col-md-6"i><"col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],
            });

            $(document).on('click', '.clickpayment', function (e) {
                e.preventDefault();

                var id = $(this).data('id');
                Swal.fire({
                    title: 'Xác nhận thanh toán?',
                    text: "Bạn có chắc muốn thanh toán đơn hàng này không?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, thanh toán!',
                    cancelButtonText: 'Hủy',
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: '{{ route('customer.order.payment') }}',
                            type: 'POST',
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}', // CSRF token
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Thành công!',
                                        'Đơn hàng đã được thanh toán.',
                                        'success'
                                    ).then(() => {
                                        location.reload(); // Tải lại trang
                                    });
                                } else {
                                    Swal.fire('Lỗi!', response.message, 'error');
                                }
                            },
                            error: function (xhr) {
                                // Kiểm tra lỗi trả về từ server
                                if (xhr.status ===400 ) {
                                    Swal.fire({
                                        title: 'Không đủ tiền!',
                                        text: 'Tài khoản của bạn không đủ tiền để thanh toán. Vui lòng nạp thêm tiền.',
                                        icon: 'error',
                                        confirmButtonText: 'Nạp tiền',
                                    }).then(() => {
                                        window.location.href = '{{ route('payment.recharge') }}'; // Đường dẫn đến trang nạp tiền
                                    });
                                } else {
                                    Swal.fire(
                                        'Lỗi!',
                                        xhr.responseJSON?.error || 'Có lỗi xảy ra, vui lòng thử lại!',
                                        'error'
                                    );
                                }
                            },
                        });
                    }
                });
            });

        });
</script>
@endpush
