@extends('backend.layouts.master')

@section('content')
<div class="content">
    <!-- Bảng danh sách danh mục -->
    <div class="category-list">
        <table class="table table-striped table-hover" id="categoryTable">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã</th>
                    <th>Tài khoản </th>
                    {{-- <th>Chi tiết</th> --}}
                    <th>Tổng tiền (Vnđ)</th>
                    <th>Nội dung</th>
                    <th>Ngày thanh toán</th>
                    <th>Hoạt động</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    td, th{
        text-align: center;
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
</style>

@endpush

@push('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        var roleId = {{ Auth::user()->role_id }}; // Lấy role_id của người dùng từ Laravel
        var APP_URL = '{{ env('APP_URL') }}';
        // Khởi tạo mảng cột
        var columns = [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'code', name: 'code' },
            { data: 'user_id', name: 'user_id' },
            { data: 'amount', name: 'amount' },
            { data: 'description', name: 'description' },
            { data: 'created_at', name: 'created_at' }
        ];

        // Nếu role_id == 1, thêm cột "Hoạt động"
        if (roleId == 1) {
            columns.push({ data: 'action', name: 'action', orderable: false, searchable: false });
        }

        // Cấu hình columnDefs
        var columnDefs = [
            { width: '8%', targets: 0 },
            { width: '15%', targets: 1 },
            { width: '25%', targets: 2 },
            { width: '15%', targets: 3 },
            { width : '25%', targets: 4 }
            // { width : '15%', targets: 5 }
        ];

        if (roleId == 1) {
            columnDefs.push({ width: '15%', targets: 5 }); // Thêm cột thứ 6 nếu role_id == 1
        }

        // Khởi tạo DataTable
        $('#categoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: APP_URL + '/history' ,
            columns: columns,
            columnDefs: columnDefs,
            pagingType: "full_numbers", 
            language: {
                paginate: { previous: '&laquo;', next: '&raquo;' },
                lengthMenu: "Hiển thị _MENU_ mục mỗi trang",
                zeroRecords: "Không tìm thấy dữ liệu",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                infoEmpty: "Không có dữ liệu để hiển thị",
                infoFiltered: "(lọc từ _MAX_ mục)"
            },
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>t<"row"<"col-md-6"i><"col-md-6"p>>',
            lengthMenu: [10, 25, 50, 100],
        });

        // Ẩn tiêu đề và cột "Hoạt động" nếu role_id != 1
        if (roleId != 1) {
            $('th:contains("Hoạt động")').hide();
        }
    });

    // Hàm xác nhận xóa
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
