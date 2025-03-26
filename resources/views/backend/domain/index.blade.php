@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <div style="overflow-x: auto;">
                <table class="table table-striped table-hover" id="categoryTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th class="text-center">Domain</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Ngày bắt đầu</th>
                            <th class="text-center">Ngày kết thúc</th>
                        </tr>
                    </thead>
                </table>
            </div>
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

        td,
        th {
            text-align: center !important;
        }

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            text-transform: capitalize;
            line-height: 1.5;
            white-space: nowrap;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status .icon-check,
        .status .icon-warning {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 8px;
            background-size: contain;
            background-repeat: no-repeat;
        }


        .status.active {
            background-color: #e6f4ea;
            color: #2b8a3e;
            border: 1px solid #cce7d0;
        }

        .status.active .icon-check {
            background-image: url('https://cdn-icons-png.flaticon.com/512/845/845646.png');
        }

        .status.paused {
            background-color: #fdecea;
            color: #d93025;
            border: 1px solid #f5c6cb;
        }

        .status.paused .icon-warning {
            background-image: url('https://cdn-icons-png.flaticon.com/512/1828/1828843.png');
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var APP_URL = '{{ env('APP_URL') }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/domain',
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
                        data: 'name',
                        name: 'name',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false
                    },
                    {
                        data: 'created_date',
                        name: 'created_date',
                        orderable: false
                    },
                    {
                        data: 'expiration_date',
                        name: 'expiration_date',
                        orderable: false
                    },

                ],
                columnDefs: [
                    {
                        width: '15%',
                        targets: 0
                    },
                    {
                        width: '30%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 2
                    },
                    {
                        width: '22%',
                        targets: 3
                    },
                    {
                        width: '23%',
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
        });


    </script>
@endpush
