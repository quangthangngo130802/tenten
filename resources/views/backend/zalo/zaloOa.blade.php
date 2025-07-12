@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên Oa</th>
                        <th>Chủ sở hữu</th>
                        <th>Số điệnt thoại</th>
                        <th>Tin nhắn đã gửi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        td,
        th {
            text-align: center !important;
        }

        td a {
            padding: 8px 11px !important;
            border-radius: 5px;
            color: white;
            display: inline-block;
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
                    data: 'name',
                    name: 'name',
                    orderable: false,
                },
                {
                    data: 'owner',
                    name: 'owner',
                    orderable: false,
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: false,
                },
                {
                    data: 'total_messages',
                    name: 'total_messages',
                    orderable: false,
                },
            ];

            // Khởi tạo DataTable
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/zalo/oa',
                columns: columns,

                order: [
                    [5, 'desc']
                ],
                pagingType: "full_numbers",
                // fixedHeader: true, // Giữ cố định tiêu đề và phần tìm kiếm
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


        });
    </script>
@endpush
