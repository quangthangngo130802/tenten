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
                    <th>Điện thoại</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                    <th class="text-center">Oa</th>
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
    .dt-column-order{
        display: none !important;
    }
   .dt-column-title{
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
                    data: 'name',
                    name: 'name',
                    orderable: false,
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: false,
                },
                {
                    data: 'sent_at',
                    name: 'sent_at',
                    orderable: false,
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                },
                {
                    data: 'oa',
                    name: 'oa',
                    orderable: false,
                },
            ];

            // Khởi tạo DataTable
            $('#categoryTable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/zalo/messages',
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
        });
    </script>
@endpush
