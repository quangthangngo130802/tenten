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
                    <li class="col-md-3 col-12"><span>Ngày mua:</span>
                        <p>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s') }}</p>
                    </li>
                    <li class="col-md-3 col-12"><span>Trạng thái:</span>
                        <p>
                            Đã thanh toán
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
                                <td>Thao tác</td>
                                <td>Tên miền</td>
                                <td>Thời hạn</td>
                                <td>Số tiền</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>
                                    Đăng ký mới
                                </td>
                                <td>
                                    .vn
                                </td>
                                <td>
                                    <span class="using">Đã xử lý</span>
                                </td>
                                <td>
                                    aicfo.vn
                                </td>
                                <td>
                                    1 năm
                                </td>
                                <td><label style="text-decoration: inherit;">450.000 đ</label></td>
                            </tr>
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
        border-top: 1px solid #ddd;
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
    .detail{
        display: flex;
    }
    p {
        font-size: 12px;
        color: #999;
        margin: 0px !important;
        line-height: normal;
    }
    .order_detailds p{
        font-weight: bold !important;
        color: #000000;
    }
</style>

@endpush
