@extends('backend.layouts.master')

@section('content')
<div class="content">
    <!-- Bảng danh sách danh mục -->
    <div class="category-list">
        <table class="table table-striped table-hover" id="categoryTable">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Chi tiết</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái thanh toán</th>
                    <th>Ngày đặt hàng</th>
                    <th>Thanh toán</th>
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
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('customer.order.index', ['status' => request()->status]) }}',
                columns: [
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'detail',
                        name: 'detail'
                    },
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
                        name: 'payment'
                    },

                ],
                columnDefs: [{
                        width: '16%',
                        targets: 0
                    },
                    {
                        width: '16%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 1
                    },

                    {
                        width: '20%',
                        targets: 1
                    },


                ],
            //   fixedHeader: true, // Giữ cố định tiêu đề và phần tìm kiếm
            //     scrollX: true,
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
        });
</script>
@endpush
