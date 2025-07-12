@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <div class="card-tools d-flex justify-content-end ">
                <a href="{{ route('hosting.create') }}" class="btn btn-primary btn-sm">Thêm mới (+)</a>
            </div>
            <div style="overflow-x: auto;">
                <table class=" table table-striped table-hover" id="categoryTable">
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
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/hosting',
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
                        data: 'bandwidth',
                        name: 'bandwidth',
                        orderable: false
                    },
                    {
                        data: 'website_limit',
                        name: 'website_limit',
                        orderable: false
                    },
                    {
                        data: 'price',
                        name: 'price',
                        orderable: false
                    },
                    {
                        data: 'backup_frequency',
                        name: 'backup_frequency',
                        orderable: false
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
                        width: '10%',
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
                    {
                        width: '10%',
                        targets: 5
                    },
                    {
                        width: '20%',
                        targets: 6
                    }
                    // {
                    //     width: '15%',
                    //     targets: 6
                    // }
                ],
               c
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
                    // Nếu người dùng xác nhận, submit form xóa
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
