@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <div class="card-tools mb-3">

                <div class="row justify-content-end">
                    {{-- <div class="col-md-2 col-6 mb-2">
                        <a href="{{ route('email.index', ['type_id' => 1]) }}"
                            class="btn btn-sm {{ request()->type_id == 1 ? 'btn-info' : 'btn-outline-primary' }}">
                            Email Premium
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <a href="{{ route('email.index', ['type_id' => 2]) }}"
                            class="btn btn-sm {{ request()->type_id == 2 ? 'btn-info' : 'btn-outline-primary' }}">
                            Email Server thường
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <a href="{{ route('email.index', ['type_id' => 3]) }}"
                            class="btn btn-sm {{ request()->type_id == 3 ? 'btn-info' : 'btn-outline-primary' }}">
                            Zshield
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-2">
                        <a href="{{ route('email.index', ['type_id' => 4]) }}"
                            class="btn btn-sm {{ request()->type_id == 4 ? 'btn-info' : 'btn-outline-primary' }}">
                            Email Pro
                        </a>
                    </div> --}}

                    <div class="col-md-2 col-6 mb-2">
                        <a href="{{ route('email.create') }}" class="btn btn-success btn-sm">Thêm mới (+)</a>
                    </div>
                </div>


            </div>
            <div style="overflow-x: auto;">
                <table class="table table-striped table-hover" id="categoryTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên gói</th>
                            <th>Dung lượng (GB)/User </th>
                            <th>Domain Alias</th>
                            <th>Email gửi/giờ/User </th>
                            <th>Backup(ngày)</th>
                            <th>Giá/tháng</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .col-md-2 a {
            width: 100%;
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
            // var typeId = '{{ request()->type_id }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/email',
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
                        data: 'package_name',
                        name: 'package_name',
                        orderable: false
                    },
                    {
                        data: 'storage',
                        name: 'storage',
                        orderable: false
                    },
                    {
                        data: 'domain_alias',
                        name: 'domain_alias',
                        orderable: false
                    },
                    {
                        data: 'sender_hour',
                        name: 'sender_hour',
                        orderable: false
                    },
                    {
                        data: 'backup',
                        name: 'backup',
                        orderable: false
                    },

                    {
                        data: 'price',
                        name: 'price',
                        orderable: false
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
                        width: '20%',
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
                        width: '15%',
                        targets: 4
                    },
                    {
                        width: '1%',
                        targets: 5
                    },
                    {
                        width: '15%',
                        targets: 6
                    },
                    {
                        width: '15%',
                        targets: 7
                    },

                ],
                order: [],
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
@endpush
