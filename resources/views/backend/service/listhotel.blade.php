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
                        <th>Khu vực</th>
                        <th>Ngày Bắt đầu/Kết thúc</th>
                        {{-- <th>Ngày kết thúc</th> --}}
                        {{-- <th>Trạng thái</th> --}}
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
            var date = '{{ $date }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/service/list-hotel/' + date,
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
                        data: 'provinces',
                        name: 'provinces',
                        orderable: false,
                    },
                    {
                        data: 'time_info',
                        name: 'time_info'
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
        });
    </script>


<script src="{{ asset('backend/service/js/serivce.js') }}?v=123"></script>
@endpush
