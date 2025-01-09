@extends('backend.layouts.master')

@section('content')
<div class="content ">
    {{-- <h1 class="mb-4 text-center">Bảng Giá Tên Miền</h1> --}}
    <!-- Form Tìm Kiếm -->
    <div class="row justify-content-between">
        <form method="GET" action="" class="mb-3 col-md-4">
            <div class="d-flex align-items-center">
                <label for="limit" class="mb-0 me-2">Hiển thị:</label>
                <select id="limit" name="limit" class="form-select " style="width: 200px;"
                    onchange="this.form.submit()">
                    <option value="10" @selected(request('limit')==10)>10</option>
                    <option value="20" @selected(request('limit')==20)>20</option>
                    <option value="50" @selected(request('limit')==50)>50</option>
                    <option value="100" @selected(request('limit')==100)>100</option>
                </select>
            </div>
        </form>

        <form action="{{ url()->current() }}" method="GET" class="mb-4 col-md-4">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                        placeholder="Nhập tên miền để tìm kiếm (.vn, .com)">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bảng Dữ Liệu -->
    <div class="category-list">
        <div style="overflow-x: auto;">
            <table class="table  table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên miền</th>
                        <th>Giá (VNĐ)</th>
                        <th>Thuế VAT (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($domain as $key => $item)
                    <tr>
                        <td>{{ $loop->iteration + (($domain->currentPage() - 1) * $domain->perPage()) }}</td>
                        <td>{{ $key }}</td>
                        <td>{{ number_format($item['total'], 0, ',', '.') }}</td>
                        <td>{{ number_format($item['vat'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Không có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Phân Trang -->
            <div class="d-flex justify-content-center">
                {{ $domain->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .text-muted {
        display: none;
    }

    .table {
        text-align: center;
    }
</style>
@endpush
