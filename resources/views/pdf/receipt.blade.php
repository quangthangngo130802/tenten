<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Đề Xuất Mua Hàng</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('DejaVuSans.ttf') format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            line-height: 1.45;
        }

        .proposal {
            margin: auto;
            padding: 5mm;
            background: #ffffff;

        }

        .proposal-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .proposal-header img {
            width: 150px;
            margin-bottom: 10px;
        }

        .proposal-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .proposal-header p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .proposal-info {
            margin-bottom: 20px;
        }

        .proposal-info p {
            font-size: 14px;
            margin: 8px 0;
        }

        .proposal-info p span {
            font-weight: bold;
        }

        .proposal-items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .proposal-items th,
        .proposal-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .proposal-items th {
            background: #ffffff;
            font-size: 14px;
            font-weight: bold;
        }

        .proposal-items td {
            font-size: 14px;
        }

        .proposal-footer {
            margin-top: 30px;
        }

        .proposal-footer .signatures {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }


        /* Media Query for better print formatting */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .proposal {
                width: 100%;
                height: auto;
                margin: 0;
                padding: 10mm;
                box-shadow: none;
                border: none;
            }

            .proposal-header h2 {
                font-size: 26px;
            }

            .proposal-info p,
            .proposal-items th,
            .proposal-items td {
                font-size: 12px;
            }

            .signature span {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="proposal">
        <!-- Header Section -->
        <div class="proposal-header">
            <img src="logo-placeholder.png" alt="Logo">
            <h2>Phiếu Mua Hàng</h2>
            <h3>CÔNG TY CP CÔNG NGHỆ VÀ TRUYỀN THÔNG SGO VIỆT NAM</h3>
            <p>Địa chỉ: Tầng 4 Số 30 Ngõ 168 Nguyễn Xiển - Thanh Xuân - Hà Nội</p>
            <p>Điện thoại: 0246.29.27.089 / Hotline: 0912.399.322 | Email: info@sgomedia.vn</p>
        </div>

        <!-- Proposal Information Section -->
        <div class="proposal-info">
            <p><span>Ngày mua hàng :</span> 31/12/2024</p>
            <p><span>Mã số thuế :</span>{{ $thongtin['ownerid'] }}</p>
            <p><span>Tên tổ chức / Cá nhân :</span> {{ $thongtin['name'] }}</p>
            <p><span>Địa chỉ xuất hóa đơn :</span> {{ $thongtin['address'] }}</p>
            <p><span>Email nhận hóa đơn điện tử :</span> {{ $thongtin['email'] }}</p>
            <p><span>Điện thoại :</span> {{ $thongtin['phone'] }}</p>
        </div>

        <!-- Product List Section -->
        <div class="proposal-items">
            <table>
                <thead>
                    <tr>
                        <th>Số thứ tự</th>
                        <th>Tên gói</th>
                        <th>Thời gian</th>
                        <th>Đơn giá</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($order->orderDetail as $index =>  $item)
                    <?php
                        if($item->type == 'hosting'){
                            $product = \App\Models\Hosting::find($item->product_id);
                            $os = '';
                            $backup = '';
                        } else {
                            $product = \App\Models\Cloud::find($item->product_id);
                            $os = ' - '.$item->os->name;
                            $backup =  $item->backup ? ' - Tự backup' : '';
                        }
                        $name =  $product->package_name.$os.$backup;
                    ?>
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $name }}</td>
                        <td>
                            @if($item->number >= 12)
                                @if($item->number % 12 == 0)
                                    {{ $item->number / 12 }} năm
                                @else
                                    {{ floor($item->number / 12) }} năm {{ $item->number % 12 }} tháng
                                @endif
                            @else
                                {{ $item->number }} tháng
                            @endif
                        </td>


                        <td>{{ number_format($item->price, 0, ',', '.') }} VND</td>
                    </tr>


                    @empty
                    <p>No order details available.</p>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="proposal-footer" style="text-align: end">
            <div class="totals" style="text-align: end">
                <p><strong>Tổng cộng:</strong> {{ number_format($price, 0, ',', '.') }}</p>
            </div>
        </div>

    </div>
</body>

</html>
