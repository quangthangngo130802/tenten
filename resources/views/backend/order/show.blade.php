@extends('backend.layouts.master')

@section('content')
<h2 class="title_page" style="text-align: center">Quản lý đơn hàng</h2>
<div class="qb_order_has_been_paid_page qb_unpaid_order_page">
    <div class="home_page_ct">

        <div class="qb_dhctt_page qb_dhdtt_mb">

            <div class="order_detailds">
                <h3>Chi tiết đơn hàng</h3>
                <ul class="row">
                    <li class="col-md-3 col-12"><span>Mã đơn hàng:</span>
                        <p>{{ $order->code }}</p>
                    </li>
                    @if ($order->status == 'active')
                    <li class="col-md-3 col-12"><span>Ngày kích hoạt:</span>
                        <p>{{ \Carbon\Carbon::parse($order->active_at)->format('d/m/Y') }}</p>
                    </li>
                    @else
                    <li class="col-md-3 col-12"><span>Ngày mua:</span>
                        <p>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</p>
                    </li>
                    @endif
                    {{-- <li class="col-md-3 col-12"><span>Ngày mua:</span>
                        <p>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s') }}</p>
                    </li> --}}
                    <li class="col-md-3 col-12"><span>Trạng thái:</span>
                        <p class="order-status">
                            @if ($order->status == 'payment')
                            Đã thanh toán <span class="status-pending">(Chờ kích hoạt)</span>
                            @elseif ($order->status == 'nopayment')
                            Chưa thanh toán
                            @elseif ($order->status == 'pending')
                            <span class="status-pending">Đang chờ cấp tài khoản</span>
                            @else
                            Đã kích hoạt
                            @endif
                        </p>

                    </li>
                </ul>
            </div>
            <div class="order_price_detailds">
                <div class="mobile_configuration">
                    <table class="order_price_info tg_navi_2021">
                        <tbody>
                            <tr>
                                <td>STT</td>
                                <td>Loại đơn hàng</td>
                                <td>Dịch vụ</td>
                                <td>Thời gian</td>
                                <td>Thao tác</td>
                                <td>Số tiền</td>
                            </tr>
                            {{-- @dd($order->orderDetail); --}}
                            @forelse ($order->orderDetail as $index => $item )
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($order->order_type == 1)
                                    Đăng ký mới
                                    @else
                                    Gia hạn
                                    @endif
                                </td>
                                <td>
                                    <?php
                                        if($item->type == 'hosting'){
                                            $product = \App\Models\Hosting::find($item->product_id);
                                            $backup = '';
                                            $domain = ' ( ' .$item->domain. ' )';
                                        } else if($item->type == 'cloud') {
                                            $product = \App\Models\Cloud::find($item->product_id);
                                            $backup = ' - '.$item->os->name;
                                            $domain = '';
                                        }else if($item->type == 'email'){
                                            $product = \App\Models\Email::find($item->product_id);
                                            $backup = '';
                                            $domain = ' ( ' .$item->domain. ' )';
                                        }else if($item->type == 'domain'){
                                            $product_name =  $item->domain .$item->domain_extension;
                                        }
                                        $name = '( ' . ucfirst($item->type) . ' ) ' . (($item->type == 'domain') ? $product_name :$product->package_name . $backup . $domain );

                                    ?>
                                    {{ $name }}
                                </td>
                                <td>@if ($item->number >= 12)
                                    @if ($item->number % 12 == 0)
                                    {{ intval($item->number / 12) }} năm
                                    @else
                                    {{ intval($item->number / 12) }} năm và {{ $item->number % 12 }} tháng
                                    @endif
                                    @else
                                    {{ $item->number }} tháng
                                    @endif
                                </td>
                                <td>

                                    <div style="display: flex; align-items: center;">
                                        <div class="status-badge">
                                            @if ($item->status == 'payment')
                                            <i class="status-icon pending"></i> Đã thanh toán (Chờ kích hoạt)
                                            @elseif ($item->status == 'nopayment')
                                            <i class="status-icon nopayment"></i> Chưa thanh toán
                                            @elseif ($item->status == 'pending')
                                            <i class="status-icon pending"></i> <span class="status-text">Đang chờ cấp
                                                tài khoản</span>
                                            @else
                                            <i class="status-icon payment"></i> Đã kích hoạt
                                            @endif
                                        </div>
                                        <div style="margin-left: 20px;">
                                            @if ($item->status == 'pending')
                                            <button class="btn btn-primary open-modal-btn"
                                                style="padding: 5px 10px; border-radius: 5px;" data-bs-toggle="modal"
                                                data-bs-target="#createAccountModal" data-id="{{ $item->id }}"
                                                data-content="{{ $item->content ?? '' }}">
                                                Tạo tài khoản
                                            </button>
                                            @endif
                                        </div>

                                        <div class="modal fade" id="createAccountModal" tabindex="-1"
                                            aria-labelledby="createAccountModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('order.create.account', ['id' => '__id__']) }}"
                                                        method="POST" id="dynamicForm">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="createAccountModalLabel">Nội
                                                                dung</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <textarea class="form-control" id="dynamicContent"
                                                                name="content" rows="10"></textarea>
                                                            <p id="error-message" class="text-danger mt-2"
                                                                style="display: none;">Nội dung không được để trống!</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Đóng</button>
                                                            <button type="submit" class="btn btn-primary"
                                                                id="submitButton">Lưu</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                    </div>


                                </td>

                                <td><label style="text-decoration: inherit;">{{ number_format($item->price) }} đ</label>
                                </td>
                            </tr>
                            @empty

                            @endforelse

                        </tbody>
                    </table>
                </div>
                <div class="dhctt_total_price col-md-3 col-12 ">
                    <div class="total_price">
                        <ul>
                            <li>
                                <span class="vat_vn_doamin">VAT <p style="display: none;">Đối với dịch vụ tên miền “.VN
                                        sẽ gồm phí, lệ phí nộp ngân sách và dịch tài khoản quản trị tên miền của TENTEN.
                                        VAT sẽ được tính trên dịch vụ tài khoản quản trị tên miền</p></span>
                                <p>0 đ</p>
                            </li>
                            <li>
                                <span class="vat_vn_doamin">Tổng tiền </span>
                                <p>{{ number_format($order->amount) }} đ</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .cke_notifications_area {
        display: none;
    }

    .error {
        color: red;
    }

    .qb_order_has_been_paid_page {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .title_page {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }

    .order_detailds {
        margin-bottom: 20px;
    }

    .order_detailds h3 {
        font-size: 18px;
        color: #555;
        margin-bottom: 10px;
    }

    .order_detailds ul {
        list-style-type: none;
        padding: 0;
    }

    .order_detailds li {
        margin-bottom: 10px;
        font-size: 16px;
    }

    .order_detailds span {
        font-weight: 600;
        color: #666;
    }

    .order_price_detailds {
        /* border-top: 1px solid #ddd; */
        padding-top: 20px;
    }

    .mobile_configuration {
        overflow-x: auto;
    }

    .order_price_info {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .order_price_info td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .order_price_info th {
        background-color: #f2f2f2;
        padding: 10px;
        text-align: left;
    }

    .dhctt_total_price {
        padding: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .total_price ul {
        list-style-type: none;
        padding: 0;
    }

    .total_price li {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        align-items: center;
    }

    .total_price strong {
        color: #333;
    }

    .vat_vn_doamin p {
        font-size: 12px;
        color: #999;

    }

    .detail {
        display: flex;
    }

    p {
        font-size: 12px;
        color: #999;
        margin: 0px !important;
        line-height: normal;
    }

    .order_detailds p {
        font-weight: bold !important;
        color: #000000;
    }

    /* Cấu trúc chung */
    .status-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid;
    }

    .status-icon {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin-right: 5px;
        display: inline-block;
        position: relative;
    }

    /* Đã xử lý */
    .status-badge.payment {
        background-color: #e6f4ea;
        /* Màu nền */
        color: #28a745;
        /* Màu chữ */
        border: 1px solid #c3e6cb;
        /* Viền */
    }

    .status-icon.payment {
        background-color: #28a745;
        /* Màu biểu tượng */
    }

    .status-icon.payment::after {
        content: "";
        width: 6px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        position: absolute;
        top: 1px;
        left: 4px;
        display: block;
    }

    /* Chờ xử lý */
    .status-badge.nopayment {
        background-color: #fff4e6;
        /* Màu nền */
        color: #ffc107;
        /* Màu chữ */
        border: 1px solid #ffeeba;
        /* Viền */
    }

    .status-icon.nopayment {
        background-color: #ffc107;
        /* Màu biểu tượng */
    }

    .status-icon.nopayment::after {
        content: "";
        width: 8px;
        height: 8px;
        background-color: white;
        border-radius: 50%;
        position: absolute;
        top: 3px;
        left: 3px;
    }

    /* Chưa duyệt */
    .status-badge.pending {
        background-color: #f8d7da;
        /* Màu nền */
        color: #f0bc5c;
        /* Màu chữ */
        border: 1px solid #f5c6cb;
        /* Viền */
    }

    .status-icon.pending {
        background-color: #f0bc5c;
        /* Màu biểu tượng */
    }

    .status-icon.pending::after {
        content: "!";
        font-size: 10px;
        color: white;
        position: absolute;
        top: -1px;
        left: 4px;
    }
</style>

@endpush

@push('scripts')

<script src="https://cdn.ckeditor.com/4.19.1/standard-all/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor4/ckeditor.js"></script>
<!-- Thêm SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    CKEDITOR.replace('dynamicContent', {
        toolbar: [
            { name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
            { name: 'clipboard', items: [ 'Undo', 'Redo' ] },
            { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt' ] },
            { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
            '/',
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', '-', 'Subscript', 'Superscript', '-', 'Strike', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
            { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
            '/',
            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'tools', items: [ 'Maximize', 'ShowBlocks', '-' ] },
            { name: 'about', items: [ 'About' ] }
        ],
        extraPlugins: 'font,colorbutton,justify',
        fontSize_sizes: '11px;12px;13px;14px;15px;16px;18px;20px;22px;24px;26px;28px;30px;32px;34px;36px',
    });
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('createAccountModal');
        const dynamicContent = document.getElementById('dynamicContent');
        const dynamicForm = document.getElementById('dynamicForm');
        const errorMessage = document.getElementById('error-message');
        const submitButton = document.getElementById('submitButton');

        // Listen for modal show event
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Extract data attributes
            const itemId = button.getAttribute('data-id');
            const itemContent = button.getAttribute('data-content');

            // Update modal content and form action dynamically
            dynamicContent.value = itemContent;
            dynamicForm.action = `{{ route('order.create.account', ['id' => '__id__']) }}`.replace('__id__', itemId);
            errorMessage.style.display = 'none'; // Reset error message
        });

        // Validate content before submitting
        dynamicForm.addEventListener('submit', function (event) {
            CKEDITOR.instances.dynamicContent.updateElement();
            if (dynamicContent.value.trim() === '') {
                event.preventDefault(); // Stop form submission
                errorMessage.style.display = 'block'; // Show error message
            }
        });

        // Clear content when modal is hidden (optional)
        modal.addEventListener('hidden.bs.modal', function () {
            dynamicContent.value = '';
            errorMessage.style.display = 'none'; // Hide error message
        });
    });
</script>
@endpush
