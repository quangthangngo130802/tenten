@extends('backend.layouts.master')

@section('content')
<div class="content">
    <!-- Bảng danh sách danh mục -->
    <div class="category-list">
        <div class="card-tools mb-3">

            <div class="row">
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('customer.cloud.index', ['type_id' => 1]) }}"
                        class="btn btn-sm {{ request()->type_id == 1 ? 'btn-info' : 'btn-outline-primary' }}">
                        Cloud Server Linux
                    </a>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('customer.cloud.index', ['type_id' => 2]) }}"
                        class="btn btn-sm {{ request()->type_id == 2 ? 'btn-info' : 'btn-outline-primary' }}">
                        Cloud Server Windows
                    </a>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('customer.cloud.index', ['type_id' => 3]) }}"
                        class="btn btn-sm {{ request()->type_id == 3 ? 'btn-info' : 'btn-outline-primary' }}">
                        Turbo Cloud Server
                    </a>
                </div>
            </div>


        </div>

        <table class="table table-striped table-hover" id="categoryTable">
            <thead>
                <tr>
                    <th>Tên gói</th>
                    <th>CPU</th>
                    <th>RAM</th>
                    <th>SSD</th>
                    <th>Mạng</th>
                    <th>Giá(Vnđ/tháng)</th>
                    <th>tổng tiền(Vnđ/năm)</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* .dataTables_scrollBody thead tr {
        display: none;
    } */
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var APP_URL = '{{ env('APP_URL') }}';
        var typeId = '{{ request()->type_id }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/customer/cloud/' + typeId,
                columns: [
                    {
                        data: 'package_name',
                        name: 'package_name'
                    },
                    {
                        data: 'cpu',
                        name: 'cpu'
                    },
                    {
                        data: 'ram',
                        name: 'ram'
                    },

                    {
                        data: 'ssd',
                        name: 'ssd'
                    },
                    {
                        data: 'network',
                        name: 'network'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'total_cost',
                        name: 'total_cost'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        width: '20%',
                        targets: 0
                    },
                    {
                        width: '10%',
                        targets: 1
                    },
                    {
                        width: '10%',
                        targets: 2
                    },
                    {
                        width: '10%',
                        targets: 3
                    },

                    {
                        width: '10%',
                        targets: 4
                    },
                    {
                        width: '15%',
                        targets: 5
                    },
                    {
                        width: '15%',
                        targets: 6
                    },
                    {
                        width: '25%',
                        targets: 7
                    },

                ],
                // scrollX: true,
                // pagingType: "full_numbers", // Kiểu phân trang
                // language: {
                //      paginate: {
                //         previous: '&laquo;', // Nút trước
                //         next: '&raquo;' // Nút sau
                //     },
                //     lengthMenu: "Hiển thị _MENU_ mục mỗi trang",
                //     zeroRecords: "Không tìm thấy dữ liệu",
                //     info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                //     infoEmpty: "Không có dữ liệu để hiển thị",
                //     infoFiltered: "(lọc từ _MAX_ mục)"
                // },
                // dom: '<"row"<"col-md-6"l><"col-md-6"f>>t<"row"<"col-md-6"i><"col-md-6"p>>',
                // lengthMenu: [10, 25, 50, 100],
            });

            $(document).on("click", ".buy-now-btn", function (event) {
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
                            url: APP_URL +'/add-to-cart',
                            type: 'POST',
                            data: {
                                item_id: itemId,
                                type: type,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('Thành công!', 'Sản phẩm đã được thêm vào giỏ hàng.', 'success');
                                    $('.notification').text(response.count);
                                } else {
                                    Swal.fire('Thất bại!', response.message || 'Có lỗi xảy ra.', 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Thất bại!', 'Không thể thêm vào giỏ hàng. Vui lòng thử lại.', 'error');
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
