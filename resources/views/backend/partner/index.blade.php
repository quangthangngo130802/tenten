@extends('backend.layouts.master')

@section('content')
    <div class="content">
        <!-- Bảng danh sách danh mục -->
        <div class="category-list">
            <div class="card-tools d-flex justify-content-end ">
                <a href="{{ route('partners.create') }}" class="btn btn-primary btn-sm">Thêm mới (+)</a>
            </div>
            <div style="overflow-x: auto;">
                <table class=" table table-striped table-hover" id="categoryTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ tên</th>
                            <th>Điện thoại</th>
                            <th>Email</th>
                            <th>Ngành nghề</th>
                            <th>Chức vụ</th>
                            <th>MST</th>
                            <th>Nguồn</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
<script>
    $(function () {
        $('#categoryTable').DataTable({
            processing: true,
            serverSide: true,
            ordering: false, // Tắt toàn bộ sorting
            ajax: '{{ route('partners.index') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'full_name', name: 'full_name', orderable: false },
                { data: 'company_phone', name: 'company_phone', orderable: false },
                { data: 'email', name: 'email', orderable: false },
                { data: 'industry', name: 'industry', orderable: false },
                { data: 'position', name: 'position', orderable: false },

                { data: 'tax_code', name: 'tax_code', orderable: false },
                { data: 'source', name: 'source', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });
    });
</script>

@endpush
