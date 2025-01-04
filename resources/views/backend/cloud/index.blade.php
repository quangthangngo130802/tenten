@extends('backend.layouts.master')

@section('content')
<div class="content">
    <!-- Bảng danh sách danh mục -->
    <div class="category-list">
        <div class="card-tools mb-3">

            <div class="row justify-content-end">
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('cloud.index', ['type_id' => 1]) }}"
                        class="btn btn-sm {{ request()->type_id == 1 ? 'btn-info' : 'btn-outline-primary' }}">
                        Cloud Server Linux
                    </a>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('cloud.index', ['type_id' => 2]) }}"
                        class="btn btn-sm {{ request()->type_id == 2 ? 'btn-info' : 'btn-outline-primary' }}">
                        Cloud Server Windows
                    </a>
                </div>
                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('cloud.index', ['type_id' => 3]) }}"
                        class="btn btn-sm {{ request()->type_id == 3 ? 'btn-info' : 'btn-outline-primary' }}">
                        Turbo Cloud Server
                    </a>
                </div>

                <div class="col-md-2 col-6 mb-2">
                    <a href="{{ route('cloud.create') }}" class="btn btn-success btn-sm">Thêm mới (+)</a>
                </div>
            </div>


        </div>
        <div style="overflow-x: auto;">
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
    $(document).ready(function () {
        var APP_URL = '{{ env('APP_URL') }}';
        var typeId = '{{ request()->type_id }}';
        $('#categoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: APP_URL + '/admin/cloud/' + typeId,
            order: [], // Vô hiệu hóa sắp xếp mặc định
            columns: [
                { data: 'package_name', name: 'package_name', orderable: false },
                { data: 'cpu', name: 'cpu', orderable: false },
                { data: 'ram', name: 'ram', orderable: false },
                { data: 'ssd', name: 'ssd', orderable: false },
                { data: 'network', name: 'network', orderable: false },
                { data: 'price', name: 'price', orderable: false },
                { data: 'total_cost', name: 'total_cost', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            columnDefs: [
                { width: '20%', targets: 0 },
                { width: '10%', targets: 1 },
                { width: '12%', targets: 2 },
                { width: '12%', targets: 3 },
                { width: '15%', targets: 4 },
                { width: '15%', targets: 5 },
                { width: '15%', targets: 6 },
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
