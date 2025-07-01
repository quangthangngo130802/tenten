@extends('backend.layouts.master')

@section('content')
    @if ($service)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">API Token</h6>
                <button class="btn btn-sm btn-outline-primary " style="padding: 10px 15px" onclick="regenerateToken()">🔄 Làm
                    mới</button>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" class="form-control" id="apiToken" value="{{ $service->token }}" readonly>
                    <button class="btn btn-outline-secondary" onclick="copyToken()">📋 Copy</button>
                </div>
            </div>
        </div>
    @else
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-danger">Chưa có tài khoản khách sạn không thể sử dụng api </h6>

        </div>

    </div>

    @endif

    <div class="card p-4">
        <h5 class="text-dark mb-3">1. URL: <span class="badge bg-success">POST</span></h5>
        <pre><code class="bg-light border rounded p-3 d-block text-success">https://id.sgodata.com/api/invoices</code></pre>

        <h5 class="text-dark mt-4">2. Headers:</h5>
        <table class="table table-bordered table-sm bg-white">
            <thead class="table-light">
                <tr>
                    <th style="width: 40%">Key</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>Authorization</code></td>
                    <td><code>Bearer YOUR_TOKEN</code></td>
                </tr>
                <tr>
                    <td><code>Accept</code></td>
                    <td><code>application/json</code></td>
                </tr>
            </tbody>
        </table>

        <h5 class="text-dark mt-4">3. Query Parameters:</h5>
        <table class="table table-bordered table-sm bg-white">
            <thead class="table-light">
                <tr>
                    <th>Tham số</th>
                    <th>Kiểu</th>
                    <th>Bắt buộc</th>
                    <th>Mô tả</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>domain</code></td>
                    <td><code>string</code></td>
                    <td>✔</td>
                    <td>Subdoamin <code>demotest</code> (ví dụ: <code>demotest.fasthotel.vn</code>)</td>
                </tr>
                <tr>
                    <td><code>invoice_code</code></td>
                    <td><code>string</code></td>
                    <td>✔</td>
                    <td>Mã hóa đơn bạn muốn lấy thông tin</td>
                </tr>
            </tbody>
        </table>

        <h5 class="text-dark mt-4">4. Ví dụ Response:</h5>
        <pre><code class="bg-light border rounded d-block">
{
    "status": "success",
    "data": {
      "id": 390,
      "payment_id": "HDBFIQI6CSG7",
      "booking_id": "DPCJKY2PU56E",
      "checkin_id": "NPZK9C7AK25R",
      "room_price": 700000,
      "payment_method": "Thanh toán chuyển khoản",
      "created_date": "2025-06-14 21:31:19",
      "status": 1,
      "unit_code": "COSO1",
      .....
    }
}
</code></pre>
    </div>
@endsection

@push('styles')
    <style>
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            background-color: #0d6efd;
            color: white;
            padding: 0.75rem;
            border-radius: 6px;
        }

        code {
            font-family: monospace;
            font-size: 15px;
        }

        pre {
            margin-bottom: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function copyToken() {
            const input = document.getElementById("apiToken");
            input.select();
            input.setSelectionRange(0, 99999); // dành cho mobile
            navigator.clipboard.writeText(input.value).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Đã copy token!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi khi copy!',
                    text: err,
                });
            });
        }
    </script>

    @if ($service)
        <script>
            function regenerateToken() {
                Swal.fire({
                    title: 'Bạn có chắc muốn tạo lại token?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Có, tạo lại!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('customer.fasthotelApi.token.regenerate', ['id' => $service->id]) }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                }
                            })
                            .then(async (res) => {
                                const data = await res.json();

                                if (!res.ok) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi ' + res.status,
                                        text: data?.message || 'Không tạo được token mới!'
                                    });
                                    return;
                                }

                                if (data.token) {
                                    document.getElementById("apiToken").value = data.token;
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Tạo mới thành công!',
                                        text: 'Token đã được cập nhật.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Phản hồi không hợp lệ!'
                                    });
                                }
                            })
                            .catch((error) => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi mạng',
                                    text: 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.'
                                });
                                console.error("Lỗi khi gửi request:", error);
                            });
                    }
                });
            }
        </script>
    @endif
@endpush
