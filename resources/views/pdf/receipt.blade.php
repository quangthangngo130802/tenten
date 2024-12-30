<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <head>
        <!-- Link tới font từ Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=DejaVu+Sans&display=swap" rel="stylesheet">
    </head>

    <title>Hóa Đơn Thanh Toán</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('public/fonts/dejavu-sans/DejaVuSans.ttf') format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .invoice-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            color: #333;
            font-size: 14px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .company-info {
            font-size: 14px;
            line-height: 1.5;
        }

        .company-info h2 {
            font-size: 24px;
            color: #333;
        }

        .invoice-title h1 {
            font-size: 28px;
            color: #007bff;
            text-align: right;
        }

        .invoice-title p {
            font-size: 14px;
            text-align: right;
            color: #777;
        }

        .invoice-info {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }

        .customer-info {
            margin-bottom: 20px;
        }

        .customer-info h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .customer-info p {
            font-size: 14px;
            margin: 5px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .items-table th {
            background-color: #007bff;
            color: #fff;
        }

        .items-table td {
            font-size: 14px;
        }

        .total-label {
            text-align: right;
            font-weight: bold;
        }

        .total-amount {
            font-weight: bold;
            color: #28a745;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-top: 20px;
        }

        .footer p {
            margin-top: 10px;
        }

        @media print {
            body {
                padding: 0;
            }

            .invoice-container {
                width: 100%;
                margin: 0;
                box-shadow: none;
            }

            .footer {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header: Company Info -->
        <div class="invoice-header">
            <div class="company-info">
                <h2>MyCompany</h2>
                <p>Địa chỉ: 123 Đường ABC, Thành phố XYZ</p>
                <p>Điện thoại: (123) 456-7890</p>
                <p>Mã số thuế: 123456789</p>
            </div>
            <div class="invoice-title">
                <h1>Hóa Đơn Thanh Toán</h1>
                <p>Ngày: <span id="invoice-date"></span></p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <h3>Thông Tin Khách Hàng</h3>
            <p>Tên khách hàng: <span id="customer-name">Nguyễn Văn A</span></p>
            <p>Địa chỉ: <span id="customer-address">Số 10, Đường XYZ</span></p>
            <p>Điện thoại: <span id="customer-phone">(098) 123-4567</span></p>
        </div>

        <!-- Product Info Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mô Tả</th>
                    <th>Số Lượng</th>
                    <th>Đơn Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Áo Thun Nam</td>
                    <td>2</td>
                    <td>200,000 VND</td>
                    <td>400,000 VND</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Quần Jean</td>
                    <td>1</td>
                    <td>350,000 VND</td>
                    <td>350,000 VND</td>
                </tr>
                <tr>
                    <td colspan="4" class="total-label">Tổng Cộng</td>
                    <td class="total-amount">750,000 VND</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Cảm ơn bạn đã mua hàng tại MyCompany. Chúc bạn một ngày tốt lành!</p>
            <p>Website: <a href="https://mycompany.com" target="_blank">www.mycompany.com</a></p>
        </div>
    </div>

    <script>
        // Đặt ngày hiện tại
    document.getElementById('invoice-date').textContent = new Date().toLocaleDateString('vi-VN');
    </script>
</body>

</html>
