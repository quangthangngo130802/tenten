@extends('backend.layouts.master')

@section('content')
    @if ($service)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Token</h6>
                <button class="btn btn-sm btn-outline-primary" onclick="regenerateToken()">🔄 Làm mới</button>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" class="form-control" id="apiToken" value="{{ $service->token }}" readonly>
                    <button class="btn btn-outline-secondary" onclick="copyToken()">📋 Copy</button>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">Chưa có tài khoản khách sạn, không thể sử dụng API.</div>
    @endif

    <div class="accordion" id="apiAccordion">
        {{-- API 1 --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                    1. Lấy thông tin đơn hàng <span class="badge bg-success ms-2">POST</span>
                </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#apiAccordion">
                <div class="accordion-body">
                    <p><strong>URL:</strong></p>
                    <pre><code class="bg-light border p-2 d-block">https://id.sgodata.com/api/invoices</code></pre>

                    <p><strong>Headers:</strong></p>
                    <table class="table table-bordered table-sm">
                        <tr><th>Authorization</th><td>Bearer YOUR_TOKEN</td></tr>
                        <tr><th>Accept</th><td>application/json</td></tr>
                    </table>

                    <p><strong>Query Parameters:</strong></p>
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>Tham số</th><th>Kiểu</th><th>Bắt buộc</th><th>Mô tả</th></tr></thead>
                        <tr><td><code>domain</code></td><td>string</td><td>✔</td><td>Subdomain khách sạn</td></tr>
                        <tr><td><code>invoice_code</code></td><td>string</td><td>✔</td><td>Mã hóa đơn</td></tr>
                    </table>

                    <p><strong>Response thành công:</strong></p>
<pre><code class="bg-light border rounded d-block">
{
    "status": "success",
    "message": "Thông tin đơn hàng",
    "data": {
        "id": 390,
        "payment_id": "HDBFIQI6CSG7",
        ...
    }
}
</code></pre>

                    <p><strong>Response lỗi:</strong></p>
<pre><code class="bg-light border rounded d-block">
{ "status": "error", "message": "Không tìm thấy hóa đơn" }
</code></pre>
<pre><code class="bg-light border rounded d-block">
{ "status": "error", "message": "Invalid token" }
</code></pre>
                </div>
            </div>
        </div>

        {{-- Thêm API khác tại đây --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                    2. Xác nhận thanh toán <span class="badge bg-success ms-2">POST</span>
                </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#apiAccordion">
                <div class="accordion-body">
                    <p><strong>URL:</strong></p>
                    <pre><code class="bg-light border p-2 d-block">https://id.sgodata.com/api/check-order-status</code></pre>
                    <p><strong>Headers:</strong></p>
                    <table class="table table-bordered table-sm">
                        <tr><th>Authorization</th><td>Bearer YOUR_TOKEN</td></tr>
                        <tr><th>Accept</th><td>application/json</td></tr>
                    </table>

                    <p><strong>Query Parameters:</strong></p>
                    <table class="table table-bordered table-sm">
                        <thead><tr><th>Tham số</th><th>Kiểu</th><th>Bắt buộc</th><th>Mô tả</th></tr></thead>
                        <tr><td><code>domain</code></td><td>string</td><td>✔</td><td>Subdomain khách sạn</td></tr>
                        <tr><td><code>invoice_code</code></td><td>string</td><td>✔</td><td>Mã hóa đơn</td></tr>
                        <tr><td><code>status</code></td><td>Boolean</td><td>✔</td><td>Trạng thái thanh toán true/false</td></tr>
                    </table>

                    <p><strong>Response:</strong></p>
<pre><code class="bg-light border rounded d-block">
{
    "invoice_code": "ORD123456",
    "success": true,
    "status": "paid"
}
</code></pre>

<p><strong>Response lỗi:</strong></p>
<pre><code class="bg-light border rounded d-block">
{ "status": "error", "message": "Không tìm thấy hóa đơn" }
</code></pre>
                </div>
            </div>
        </div>
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
