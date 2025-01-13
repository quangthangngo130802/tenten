@extends('backend.layouts.master')

@section('content')
<div class="content">
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentModalLabel">Nội dung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="content">

                    </div>
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

    .endday {
        color: red;
        font-size: 13px;
    }

    td,
    th {
        text-align: center;
    }

    .dropdown {
        position: relative;
        /* Ensure the dropdown menu is positioned relative to this div */
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background-color: white;
        border: 1px solid #ccc;
        z-index: 1050;
        min-width: 160px;
    }

    .action:hover .dropdown-menu {
        display: block;

    }
</style>

@endpush

@push('scripts')

<script type="text/javascript">
        $(document).ready(function() {
            var APP_URL = '{{ env('APP_URL') }}';
            var date = '{{ $date }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/customer/service/list-cloud/' + date,
                columns: [
                    {
                        data: null, // STT
                        name: 'STT',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'packagename',
                        name: 'id'
                    },
                    {
                        data: 'giahan',
                        name: 'id'
                    },

                    {
                        data: 'active_at',
                        name: 'active_at'
                    },
                    {
                        data: 'enddate',
                        name: 'number'

                    },
                    {
                        data: 'active',
                        name: 'status'

                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                        width: '8%',
                        targets: 0
                    },
                    {
                        width: '26%',
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
                    {
                        width: '15%',
                        targets: 1
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
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>t<"row"<"col-md-6"i><"col-md-6"p>>',
                lengthMenu: [10, 25, 50, 100],

            });


        });

        function openModal(id) {
            document.getElementById('content').innerHTML = '';
            document.getElementById('contentModalLabel').innerText = 'Nội dung';
            document.getElementById('contentModalLabel').setAttribute('data-id', id);
            fetch(`/cloud/getContent/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('content').innerHTML = data.content;
                })
                .catch(error => {
                    console.error('Error fetching content:', error);
                });

            // Mở modal bằng Bootstrap 5
            var myModal = new bootstrap.Modal(document.getElementById('contentModal'), {});
            myModal.show();
        }

</script>

@endpush
