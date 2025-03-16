@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên gói</th>
                        <th>Dung lượng</th>
                        <th>Băng thông</th>
                        <th>Gới hạn website</th>
                        <th>Giá/năm</th>
                        <th>Hỗ trợ backup</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
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

        .dataTables_scrollBody thead tr {
            display: none;
        }
        th, td{
            text-align: center!important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var APP_URL = '{{ env('APP_URL') }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/customer/hosting',
                columns: [
                    {
                        data: null, // Chúng ta sẽ thêm số thứ tự thủ công
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Lấy chỉ số hàng +1 để hiển thị số thứ tự
                        }
                    },
                    {
                        data: 'package_name',
                        name: 'package_name',
                        orderable: false,
                    },
                    {
                        data: 'storage',
                        name: 'storage',
                        orderable: false,
                    },
                    {
                        data: 'bandwidth',
                        name: 'bandwidth',
                        orderable: false,
                    },

                    {
                        data: 'website_limit',
                        name: 'website_limit',
                        orderable: false,
                    },
                    {
                        data: 'price',
                        name: 'price',
                        orderable: false,
                    },
                    {
                        data: 'backup_frequency',
                        name: 'backup_frequency',
                        orderable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [
                    {
                        width: '5%',
                        targets: 0
                    },
                    {
                        width: '16%',
                        targets: 1
                    },
                    {
                        width: '13%',
                        targets: 2
                    },
                    {
                        width: '15%',
                        targets: 3
                    },
                    {
                        width: '14%',
                        targets: 4
                    },

                    {
                        width: '13%',
                        targets: 5
                    },
                    {
                        width: '12%',
                        targets: 6
                    },
                    {
                        width: '15%',
                        targets: 7
                    },

                ],
                // scrollX: true,
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

                lengthMenu: [10, 25, 50, 100],
            });

            $(document).on("click", ".buy-now-btn", function(event) {
                event.preventDefault();

                const itemId = $(this).data("id");
                const type = $(this).data("type");

                Swal.fire({
                    title: 'Bạn có muốn thêm vào giỏ hàng không?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Có',
                    cancelButtonText: 'Không'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: APP_URL + '/add-to-cart',
                            type: 'POST',
                            data: {
                                item_id: itemId,
                                type: type,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Thành công!',
                                        'Sản phẩm đã được thêm vào giỏ hàng.',
                                        'success');
                                    $('.notification').text(response.count);
                                    window.location.href =
                                        '{{ route('customer.cart.listcart') }}';
                                } else {
                                    Swal.fire('Thất bại!', response.message ||
                                        'Có lỗi xảy ra.', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Thất bại!',
                                    'Không thể thêm vào giỏ hàng. Vui lòng thử lại.',
                                    'error');
                            }
                        });
                    } else {
                        Swal.fire('Đã hủy', 'Sản phẩm không được thêm vào giỏ hàng.', 'info');
                    }
                });
            });


        });
    </script>
@endpush
