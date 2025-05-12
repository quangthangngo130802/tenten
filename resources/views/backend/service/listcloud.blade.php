@extends('backend.layouts.master')

@section('content')
    <div class="content">
        @include('backend.modal.modal')

        <div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('transfer.service') }}" method="POST" id="transferForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="transferModalLabel">Chuyển dữ liệu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" name="service_id" id="data-id" class="form-control">
                            <div class="form-group">
                                <label for="domain">Tên Cloud để đổi:</label>
                                <input type="text" name="hosting" id="data-hosting" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label for="toUser">Người chuyển :</label>
                                <input type="text" id="data-email" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label for="toUser">Người nhận Cloud (Người nhận):</label>
                                <select name="username" id="username" class="form-control">
                                    {{-- <option value="">--- Chọn người nhận ---</option> --}}
                                    @foreach ($users as $user)
                                        <option value="{{ $user->email }}">{{ $user->full_name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Xác nhận chuyển</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="category-list">
            <div class="card-tools d-flex justify-content-end ">
                <a href="{{ route('service.add', ['type' => 'cloud']) }}" class="btn btn-primary btn-sm">Thêm mới (+)</a>
            </div>
            <table class="table table-striped table-hover" id="categoryTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Khách hàng</th>
                        <th>Tên gói</th>
                        {{-- <th>Gia hạn</th> --}}
                        <th>Bắt đầu</th>
                        <th>Kết thúc</th>
                        <th>Trạng thái</th>
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

    {{-- <script src="https://cdn.ckeditor.com/4.19.1/standard-all/ckeditor.js"></script> --}}
    <script src="https://cdn.ckeditor.com/ckeditor4/ckeditor.js"></script>
    <!-- Thêm SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var APP_URL = '{{ env('APP_URL') }}';
            var date = '{{ $date }}';
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: APP_URL + '/admin/service/list-cloud/' + date,
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
                        data: 'packagename',
                        name: 'packagename',
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
                        width: '15%',
                        targets: 1
                    },
                    {
                        width: '15%',
                        targets: 2
                    },
                    {
                        width: '15%',
                        targets: 3
                    },

                    {
                        width: '15%',
                        targets: 4
                    },
                    {
                        width: '20%',
                        targets: 5
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

    <script src="{{ asset('backend/service/js/serivce.js') }}"></script>



@endpush
