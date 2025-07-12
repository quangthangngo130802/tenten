@extends('backend.layouts.master')

@section('content')
    <div class="p-6 mx-auto">
        <h1 class="text-3xl font-bold mb-6">Dashboard Quản trị ZNS</h1>

        <!-- Bộ lọc thời gian -->
        <!-- Bộ lọc -->
        <!-- Nút lọc -->

        <div class="flex flex-wrap gap-2 mb-4">
            <button class="filter-btn px-4 py-2 bg-blue-600  border rounded-lg text-white" data-filter="today">Hôm nay</button>
            <button class="filter-btn px-4 py-2 bg-white border rounded-lg text-gray-700" data-filter="yesterday">Hôm
                qua</button>
            <button class="filter-btn px-4 py-2 bg-white border rounded-lg text-gray-700" data-filter="last_7_days">7 ngày
                qua</button>

            <!-- Nút hiện bộ lọc -->
            <button id="show-custom-date" class="px-4 py-2 bg-white border rounded-lg text-gray-700">Tùy chỉnh</button>

            <!-- Date range input đẹp -->
            <div id="custom-date-range" class="hidden flex-wrap gap-2 items-center">
                <input id="date-range-picker" type="text"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-60 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Chọn khoảng ngày" />
                <button id="custom-filter-btn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Lọc</button>
            </div>
        </div>






        <!-- Số liệu thống kê -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow">
                <p class="text-sm text-gray-500">Gửi thành công</p>
                <p class="text-2xl font-bold text-green-600" id="total-success">
                    {{ number_format($messageSummary['success']) }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow">
                <p class="text-sm text-gray-500">Gửi thất bại</p>
                <p class="text-2xl font-bold text-red-600" id="total-fail">{{ number_format($messageSummary['fail']) }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow">
                <p class="text-sm text-gray-500">Chi tiêu (VNĐ)</p>
                <p class="text-2xl font-bold" id="total-amount">{{ number_format($messageSummary['totalAmount']) }}đ</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow">
                <p class="text-sm text-gray-500">Nạp đang chờ</p>
                <p class="text-2xl font-bold text-yellow-600" id="total-summary">{{ number_format($summary) }}đ</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow">
                <p class="text-sm text-gray-500">Số khách hàng</p>
                <p class="text-2xl font-bold" id="total-user">{{ $userCount }}</p>
            </div>
        </div>

        <!-- Biểu đồ & khách hàng -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl p-4 shadow">
                <h2 class="text-lg font-semibold mb-2">Thống kê gửi tin</h2>
                <canvas id="messageChart" class="w-full h-64 mt-4"></canvas>
            </div>

            <div class="bg-white rounded-xl p-4 shadow">
                <h2 class="text-lg font-semibold mb-4">Tài khoản khách hàng</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">STT</th>
                                <th class="px-4 py-3">Tên KH</th>
                                <th class="px-4 py-3">SĐT</th>
                                <th class="py-3 text-center">Đã gửi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-800" id="user-table-body">
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td class="px-4 py-2">{{ $key + 1 }}</td>
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->phone }}</td>
                                    <td class="px-4 py-2">{{ $user->zns_messages_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- OA & Token -->
        <div class="bg-white rounded-xl p-4 shadow mb-6">
            <h2 class="text-lg font-semibold mb-4">Trạng thái OA & Token</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">KH</th>
                            <th class="px-4 py-3">OA ID</th>
                            <th class="px-4 py-3">Tên OA</th>
                            <th class="px-4 py-3">Ví chính</th>
                            <th class="px-4 py-3">Ví phụ</th>
                            <th class="py-3 text-center">Đã gửi</th>
                            {{-- <th class="py-3 text-center">Hạn mức tin</th>
                            <th class="py-3 text-center">Tin còn lại</th> --}}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-800" id="oa-table-body">
                        @foreach ($zaloOa as $item)
                            @php $user = $item->userZalo; @endphp
                            <tr>
                                <td class="px-4 py-2">{{ $user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $item->oa_id }}</td>
                                <td class="px-4 py-2">{{ $item->name }}</td>
                                <td class="px-4 py-2">{{ number_format($user->wallet ?? 0) }}đ</td>
                                <td class="px-4 py-2">{{ number_format($user->sub_wallet ?? 0) }}đ</td>
                                <td class="px-4 py-2 text-green-600">{{ number_format($item->zns_messages_count) }}</td>
                                {{-- <td class="px-4 py-2">1,588</td>
                                <td class="px-4 py-2 text-blue-600 font-semibold">3,412</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Giao diện Tailwind đẹp hơn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Ngôn ngữ Tiếng Việt -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
    <script>
        const chartCtx = document.getElementById('messageChart').getContext('2d');
        let chart;

        function renderChart(labels, success, fail) {
            if (chart) chart.destroy();

            chart = new Chart(chartCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Thành công',
                            data: success,
                            borderColor: '#16a34a',
                            backgroundColor: '#16a34a33',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Thất bại',
                            data: fail,
                            borderColor: '#dc2626',
                            backgroundColor: '#dc262633',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                }
            });
        }

        function number_format(num) {
            return new Intl.NumberFormat('vi-VN').format(num);
        }

        function updateDashboard(data) {
            $('#total-success').text(number_format(data.messageSummary.success));
            $('#total-fail').text(number_format(data.messageSummary.fail));
            $('#total-amount').text(number_format(data.messageSummary.totalAmount) + 'đ');
            $('#total-summary').text(number_format(data.summary) + 'đ');
            $('#total-user').text(number_format(data.userCount));


            renderChart(data.chart.labels, data.chart.successData, data.chart.failData);


            let userRows = '';
            data.users.forEach((user, idx) => {
                userRows += `<tr>
                <td class="px-4 py-2">${idx + 1}</td>
                <td class="px-4 py-2">${user.name}</td>
                <td class="px-4 py-2">${user.phone}</td>
                <td class="px-4 py-2">${user.zns_messages_count}</td>
            </tr>`;
            });
            $('#user-table-body').html(userRows);


            let oaRows = '';
            data.zaloOa.forEach((item) => {
                let user = item.user_zalo ?? {};
                oaRows += `<tr>
                <td class="px-4 py-2">${user.name ?? 'N/A'}</td>
                <td class="px-4 py-2">${item.oa_id}</td>
                <td class="px-4 py-2">${item.name}</td>
                <td class="px-4 py-2">${number_format(user.wallet ?? 0)}đ</td>
                <td class="px-4 py-2">${number_format(user.sub_wallet ?? 0)}đ</td>
                <td class="px-4 py-2 text-green-600">${number_format(item.zns_messages_count ?? 0)}</td>
              
            </tr>`;
            });
            $('#oa-table-body').html(oaRows);
        }



        flatpickr("#date-range-picker", {
            mode: "range",
            dateFormat: "Y-m-d",
            locale: "vn",
            maxDate: "today"
        });


        $('#show-custom-date').on('click', function() {
            $('#custom-date-range').removeClass('hidden').addClass('flex');


            $('.filter-btn').removeClass('bg-blue-600 text-white').addClass('bg-white border text-gray-700');
            $(this).removeClass('bg-white border text-gray-700').addClass('bg-blue-600 text-white');


            let dateRange = $('#date-range-picker').val();
            let [from, to] = dateRange.split(' đến ').map(str => str.trim());

            if (from && to) {
                $.get("{{ route('zalo.dashboard.filter') }}", {
                    filter: 'custom',
                    from: from,
                    to: to
                }, function(response) {
                    updateDashboard(response);
                }).fail(() => alert('Lỗi khi tải dữ liệu'));
            }
        });


        $('.filter-btn').on('click', function(e) {
            e.preventDefault();

            let filter = $(this).data('filter');


            $('#custom-date-range').addClass('hidden');


            $('.filter-btn').removeClass('bg-blue-600 text-white').addClass('bg-white border text-gray-700');
            $(this).removeClass('bg-white border text-gray-700').addClass('bg-blue-600 text-white');
            $('#show-custom-date').removeClass('bg-blue-600 text-white').addClass('bg-white border text-gray-700');

            $.get("{{ route('zalo.dashboard.filter') }}", {
                filter: filter
            }, function(response) {
                updateDashboard(response);
            }).fail(() => alert('Lỗi khi tải dữ liệu'));
        });


        $('#custom-filter-btn').on('click', function() {
            let dateRange = $('#date-range-picker').val();
            let [from, to] = dateRange.split(' đến ').map(str => str.trim());

            if (!from || !to) {
                alert('Vui lòng chọn đầy đủ ngày bắt đầu và kết thúc');
                return;
            }


            $('.filter-btn').removeClass('bg-blue-600 text-white').addClass('bg-white border text-gray-700');
            $(this).removeClass('bg-white border text-gray-700').addClass('bg-blue-600 text-white');



            $.get("{{ route('zalo.dashboard.filter') }}", {
                filter: 'custom',
                from: from,
                to: to
            }, function(response) {
                updateDashboard(response);
            }).fail(() => alert('Lỗi khi tải dữ liệu'));
        });



        renderChart(@json($chart['labels']), @json($chart['successData']), @json($chart['failData']));
    </script>
@endpush
