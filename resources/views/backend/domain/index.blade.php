@extends('backend.layouts.master')

@section('content')
<div class="content">
    <div class="row justify-content-between">
        <form method="GET" action="{{ route('domain.index') }}" class="row mb-3 col-md-4 align-items-center">
            <label for="limit" class="mb-0 me-2 col-md-4">Hiển thị:</label>
            <select id="limit" name="limit" class="form-select me-2 col-md-8" style="width: 200px;"
                onchange="this.form.submit()">
                <option value="10" {{ $current_limit==10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ $current_limit==20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ $current_limit==50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $current_limit==100 ? 'selected' : '' }}>100</option>
            </select>
        </form>


        <!-- Form chọn số lượng hiển thị -->

    </div>
    <div class="category-list">
        <div style="overflow-x: auto;">
            <table class="table table-striped table-hover" id="categoryTable">

                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên miền</th>
                        <th>Ngày tạo</th>
                        <th>Ngày hết hạn</th>
                        <th>trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($domains as $index => $domain)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $domain['domain_name'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($domain['created_date'])->format('d-m-Y H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($domain['expiration_date'])->format('d-m-Y H:i:s') }}</td>
                        <td>
                            <div class="status {{ $domain['domain_status'] == '0' ? 'paused' : 'active' }}">
                                <span
                                    class="{{ $domain['domain_status'] == '0' ? 'icon-warning' : 'icon-check' }}"></span>
                                {{ $domain['domain_status'] == '0' ? 'Tạm dừng' : 'Hoạt động' }}
                            </div>


                        </td>
                        <td><a href="{{ route('domain.show', ['domain' => $domain['domain_name'] ]) }}"
                                title="Chi tiết ">
                                <span class="fas fa-arrow-circle-right" style="font-size: 25px"></span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Không có dữ liệu.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
    @if(!empty($paginate))
    <div style="display: flex; justify-content: center;">
        <nav>
            <ul class="pagination">
                <!-- Nút trang đầu -->
                <li class="page-item {{ $paginate['current_page'] == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ route('domain.index', ['limit' => $current_limit, 'page' => 1]) }}">
                        &lt;&lt;
                    </a>
                </li>

                <!-- Nút trang trước -->
                <li class="page-item {{ $paginate['has_prev_page'] ? '' : 'disabled' }}">
                    <a class="page-link"
                        href="{{ route('domain.index', ['limit' => $current_limit, 'page' => max($paginate['current_page'] - 1, 1)]) }}">
                        &lt;
                    </a>
                </li>

                <!-- Hiển thị trang đầu nếu cần -->
                @if ($paginate['current_page'] > 3)
                <li class="page-item">
                    <a class="page-link" href="{{ route('domain.index', ['limit' => $current_limit, 'page' => 1]) }}">
                        1
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">...</a>
                </li>
                @endif

                <!-- Hiển thị các trang quanh trang hiện tại -->
                @php
                $page_count = $paginate['page_count'];
                $current_page = $paginate['current_page'];
                $start = max(1, $current_page - 1);
                $end = min($page_count, $current_page + 1);
                @endphp

                @for ($i = $start; $i <= $end; $i++) <li class="page-item {{ $i == $current_page ? 'active' : '' }}">
                    <a class="page-link" href="{{ route('domain.index', ['limit' => $current_limit, 'page' => $i]) }}">
                        {{ $i }}
                    </a>
                    </li>
                    @endfor

                    <!-- Hiển thị dấu "..." và trang gần cuối nếu cần -->
                    @if ($paginate['current_page'] < $page_count - 2) <li class="page-item">
                        <a class="page-link" href="#">...</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ route('domain.index', ['limit' => $current_limit, 'page' => $page_count - 1]) }}">
                                {{ $page_count - 1 }}
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ route('domain.index', ['limit' => $current_limit, 'page' => $page_count ]) }}">
                                {{ $page_count  }}
                            </a>
                        </li>
                    @endif



                        <!-- Nút trang sau -->
                        <li class="page-item {{ $paginate['has_next_page'] ? '' : 'disabled' }}">
                            <a class="page-link"
                                href="{{ route('domain.index', ['limit' => $current_limit, 'page' => min($paginate['current_page'] + 1, $paginate['page_count'])]) }}">
                                &gt;
                            </a>
                        </li>

                        <!-- Nút trang cuối -->
                        <li class="page-item {{ $paginate['current_page'] == $page_count ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ route('domain.index', ['limit' => $current_limit, 'page' => $page_count]) }}">
                                &gt;&gt;
                            </a>
                        </li>
            </ul>
        </nav>
    </div>



    @endif



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

    /* Badge chung */


    #categoryTable {
        text-align: center;
    }
</style>

@endpush
