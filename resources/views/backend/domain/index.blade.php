@extends('backend.layouts.master')

@section('content')
<div class="content">
    <div>
        <form method="GET" action="{{ route('domain.index') }}" class="mb-3">
            <div class="d-flex align-items-center">
                <label for="limit" class="mb-0 me-2">Hiển thị:</label>
                <select id="limit" name="limit" class="form-select " style="width: 200px;"
                    onchange="this.form.submit()">
                    <option value="10" {{ $current_limit==10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $current_limit==20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $current_limit==50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $current_limit==100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </form>






    </div>
    <div class="category-list">
        <div style="overflow-x: auto;">
            <table class="table table-striped table-hover" id="categoryTable">

                <thead>
                    <tr>
                        <th>#</th>
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
                            <span class="status-badge {{ $domain['domain_status'] == '0' ? 'expired' : 'using' }}">
                                <i class="status-icon">{{ $domain['domain_status'] == '0' ? '⚠' : '✔' }}</i>
                                {{ $domain['domain_status'] == '0' ? 'Tạm dừng' : 'Hoạt động' }}
                            </span>

                        </td>
                        <td><a href="{{ route('domain.show', ['domain' => $domain['domain_name'] ]) }}">Chi tiết</a>
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
    <nav>
        <ul class="pagination">
            <!-- Nút trang đầu -->
            <li class="page-item {{ $paginate['has_prev_page'] ? '' : 'disabled' }}">
                <a class="page-link" href="{{ route('domain.index', ['limit' => $current_limit, 'page' => 1]) }}">
                    Đầu
                </a>
            </li>

            <!-- Nút trang trước -->
            <li class="page-item {{ $paginate['has_prev_page'] ? '' : 'disabled' }}">
                <a class="page-link"
                    href="{{ route('domain.index', ['limit' => $current_limit, 'page' => max($paginate['current_page'] - 1, 1)]) }}">
                    Trước
                </a>
            </li>

            <!-- Hiển thị các trang -->
            @php
            $page_count = $paginate['page_count'];
            $current_page = $paginate['current_page'];
            $max_display = 5; // Số trang hiển thị tối đa
            $start = max(1, $current_page - floor($max_display / 2));
            $end = min($page_count, $start + $max_display - 1);

            // Nếu có nhiều trang, hiển thị "..."
            $show_start_ellipsis = $start > 1;
            $show_end_ellipsis = $end < $page_count; @endphp <!-- Nếu có dấu "..." ở đầu -->
                @if ($show_start_ellipsis)
                <li class="page-item">
                    <a class="page-link" href="#">...</a>
                </li>
                @endif

                <!-- Hiển thị các trang -->
                @for ($i = $start; $i <= $end; $i++) <li class="page-item {{ $i == $current_page ? 'active' : '' }}">
                    <a class="page-link" href="{{ route('domain.index', ['limit' => $current_limit, 'page' => $i]) }}">
                        {{ $i }}
                    </a>
                    </li>
                    @endfor

                    <!-- Nếu có dấu "..." ở cuối -->
                    @if ($show_end_ellipsis)
                    <li class="page-item">
                        <a class="page-link" href="#">...</a>
                    </li>
                    @endif

                    <!-- Nút trang sau -->
                    <li class="page-item {{ $paginate['has_next_page'] ? '' : 'disabled' }}">
                        <a class="page-link"
                            href="{{ route('domain.index', ['limit' => $current_limit, 'page' => min($paginate['current_page'] + 1, $paginate['page_count'])]) }}">
                            Tiếp
                        </a>
                    </li>

                    <!-- Nút trang cuối -->
                    <li class="page-item {{ $paginate['has_next_page'] ? '' : 'disabled' }}">
                        <a class="page-link"
                            href="{{ route('domain.index', ['limit' => $current_limit, 'page' => $paginate['page_count']]) }}">
                            Cuối
                        </a>
                    </li>
        </ul>
    </nav>
    @endif



</div>
@endsection

@push('styles')
<style>
    /* Badge chung */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: bold;
        border: 1px solid transparent;
    }

    /* Biểu tượng */
    .status-icon {
        display: inline-block;
        margin-right: 5px;
        font-size: 14px;
    }

    /* Trạng thái "Hoạt động" */
    .using {
        background-color: #e6f7e6;
        color: #389e0d;
        border-color: #b7eb8f;
    }

    .using .status-icon {
        content: "\2714";
        /* Dấu tick */
        color: #389e0d;
    }

    /* Trạng thái "Tạm dừng" */
    .expired {
        background-color: #fff1f0;
        color: #cf1322;
        border-color: #ffa39e;
    }

    .expired .status-icon {
        content: "\2716";
        /* Dấu X */
        color: #cf1322;
    }


    #categoryTable {
        text-align: center;
    }
</style>

@endpush
