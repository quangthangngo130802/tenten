@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered">
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
                        <th>Khách hàng</th>
                        <th>Tên miền</th>
                        {{-- <th>Gia hạn</th> --}}
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
            text-align: center !important;
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .popup-content {
            text-align: center;
        }

        .popup textarea {
            width: 100%;
            resize: none;
        }

        .popup button {
            margin: 5px;
            padding: 5px 10px;
        }


        .cke_notifications_area {
            display: none;
        }

        .error {
            color: red;
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
                ajax: APP_URL + '/customer/service/list-hotel/' + date,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_info',
                        name: 'id',
                        orderable: false,
                    },
                    {
                        data: 'domain',
                        name: 'domain',
                        orderable: false,
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
                        name: 'status',
                        orderable: false,

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
                        width: '20%',
                        targets: 1,

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

            // $('#categoryTable').on('click', '.action', function(e) {
            //     e.stopPropagation();

            //     const $currentMenu = $(this).siblings('.menu-action');

            //     $('.menu-action').not($currentMenu).hide();

            //     $currentMenu.toggle();
            // });

            // $(document).on('click', function() {
            //     $('.menu-action').hide();
            // }


        });
    </script>

    <!-- Thêm SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Hàm mở modal và cập nhật nội dung CKEditor
        window.openModal = function(id) {
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

        // Lắng nghe sự kiện khi bấm nút Lưu
    </script>
@endpush
