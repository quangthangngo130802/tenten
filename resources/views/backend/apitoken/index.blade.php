@extends('backend.layouts.master')

@section('content')
    <div class="content">

        @include('backend.modal.modal')

        <div class="category-list">
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Khách hàng</th>
                        <th>Email</th>
                        <th>Link</th>
                        <th>Token</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/service/css/service.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.19.1/standard-all/ckeditor.js"></script>
    {{-- <script src="https://cdn.ckeditor.com/ckeditor4/ckeditor.js"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var APP_URL = '{{ env('APP_URL') }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/token-hotel',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_info',
                        name: 'user_info',
                        orderable: false,
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: false,
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                    },
                    {
                        data: 'token',
                        name: 'token',
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
                        targets: 0,
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

        function regenerateToken(id) {
            Swal.fire({
                title: 'Bạn có chắc muốn làm mới token?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Có, làm mới!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/token-hotel/${id}/regenerate-token`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                "Accept": "application/json"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Làm mới thành công!',
                                    text: 'Token mới: ' + data.token,
                                    timer: 2000,
                                    showConfirmButton: true
                                });


                                $('#categoryTable').DataTable().ajax.reload(); // nếu dùng DataTable
                            } else {
                                Swal.fire('Lỗi', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error');
                        });
                }
            });
        }
    </script>



    <script src="{{ asset('backend/service/js/serivce.js') }}?v=123"></script>
@endpush
