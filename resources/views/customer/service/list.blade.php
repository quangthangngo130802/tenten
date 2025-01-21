@extends('backend.layouts.master')

@section('content')
<div class="content">

    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentModalLabel">Nội dung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="category-list">
        <table class="table table-striped table-hover" id="categoryTable">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên gói</th>
                    <th>Gia hạn</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
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
    .status.active {
        background-color: #e6f4ea;
        color: #2b8a3e;
        border: 1px solid #cce7d0;
    }
    .status.paused {
        background-color: #fdecea;
        color: #d93025;
        border: 1px solid #f5c6cb;
    }
    .endday {
        color: red;
        font-size: 13px;
    }
    td, th {
        text-align: center;
    }
</style>
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        const APP_URL = '{{ env('APP_URL') }}';
        const date = '{{ $date }}';
        const type = '{{ $type }}';
        // alert(type);
        function loadTable(serviceType) {

            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, // Phá hủy bảng cũ trước khi tạo lại
                ajax: `${APP_URL}/customer/service/list-${type}`,
                columns: [
                    {
                        data: null, // STT
                        name: 'STT',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'packagename', name: 'id' },
                    { data: 'giahan', name: 'id' },
                    { data: 'active_at', name: 'active_at' },
                    { data: 'enddate', name: 'number' },
                    { data: 'active', name: 'status' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [
                    { width: '8%', targets: 0 },
                    { width: '26%', targets: 1 },
                    { width: '15%', targets: [2, 3, 4, 5] },
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
        }

        // Gọi hàm loadTable với loại dịch vụ cụ thể
        loadTable('hosting'); // Hoặc 'cloud', 'email'

        window.openModal = function (id) {
            document.getElementById('content').innerHTML = '';
            document.getElementById('contentModalLabel').innerText = 'Nội dung';
            document.getElementById('contentModalLabel').setAttribute('data-id', id);

            fetch(`/service/getContent/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('content').innerHTML = data.content;
                })
                .catch(error => {
                    console.error('Error fetching content:', error);
                });

            const myModal = new bootstrap.Modal(document.getElementById('contentModal'), {});
            myModal.show();
        };
    });
</script>
@endpush
