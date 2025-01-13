@extends('backend.layouts.master')

@section('content')
<div class="content">
    <!-- Bảng danh sách danh mục -->
    <div class="category-list">
        <div class="card-tools mb-3">

            <div class="row justify-content-end">
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('customer.email.index', ['email_type' => 1]) }}"
                        class="btn btn-sm {{ request()->email_type == 1 ? 'btn-info' : 'btn-outline-primary' }}">
                        Email Premium
                    </a>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('customer.email.index', ['email_type' => 2]) }}"
                        class="btn btn-sm {{ request()->email_type == 2 ? 'btn-info' : 'btn-outline-primary' }}">
                        Email Server thường
                    </a>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('customer.email.index', ['email_type' => 3]) }}"
                        class="btn btn-sm {{ request()->email_type == 3 ? 'btn-info' : 'btn-outline-primary' }}">
                        Zshield
                    </a>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('customer.email.index', ['email_type' => 4]) }}"
                        class="btn btn-sm {{ request()->email_type == 4 ? 'btn-info' : 'btn-outline-primary' }}">
                        Email Pro
                    </a>
                </div>
            </div>


        </div>
        <div style="overflow-x: auto;">
        <table class="table table-striped table-hover" id="categoryTable">
            <thead>
                <tr>
                    <th>Tên gói</th>
                    <th>Dung lượng</th>
                    <th>Địa chỉ Email</th>
                    <th>Số lượng email gửi đi/ngày  </th>
                    <th>Số lượng email gửi đi/tháng</th>
                    <th>Tổng dung lượng file đính kèm/tháng (GB)</th>
                    <th>Giá theo tháng</th>
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
    .col-md-2 a{
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
</style>

@endpush

@push('scripts')

<script type="text/javascript">
    $(document).ready(function () {
        var APP_URL = '{{ env('APP_URL') }}';
        var typeId = '{{ request()->email_type }}';
        $('#categoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: APP_URL + '/customer/email/' + typeId,
            order: [], // Vô hiệu hóa sắp xếp mặc định
            columns: [
                { data: 'package_name', name: 'package_name', orderable: false },
                { data: 'storage', name: 'storage', orderable: false },
                { data: 'number_email', name: 'number_email', orderable: false },
                { data: 'sender_day', name: 'ssdsender_day', orderable: false },
                { data: 'sender_month', name: 'sender_month', orderable: false },
                { data: 'storage_file', name: 'storage_file', orderable: false },
                { data: 'price', name: 'price', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            columnDefs: [
                { width: '15%', targets: 0 },
                { width: '10%', targets: 1 },
                { width: '12%', targets: 2 },
                { width: '15%', targets: 3 },
                { width: '18%', targets: 4 },
                { width: '18%', targets: 5 },
                { width: '18%', targets: 6 },
                { width: '15%', targets: 7 }
            ],
            pagingType: "full_numbers",
            language: {
                paginate: {
                    previous: '&laquo;',
                    next: '&raquo;'
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
