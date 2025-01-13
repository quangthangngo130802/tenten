<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Hết Hạn Dịch Vụ</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }
        .header {
            background-color: #3498db;
            color: #ffffff;
            padding: 18px 15px;
            text-align: center;
            border-radius: 12px 12px 0 0;
            font-size: 22px;
            font-weight: 600;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .message {
            padding: 10x 0 0 0;
            font-size: 16px;
            line-height: 1.8;
            color: #555555;
            text-align: justify;
        }
        .message strong {
            color: #2c3e50;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777777;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            margin-top: 15px;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $details['title'] }}</h1>
        </div>
        <div class="message">
            <p>{{ $details['message'] }}</p>
        </div>
        <div class="footer">
            <p>Để biết thêm chi tiết hoặc gia hạn dịch vụ, vui lòng liên hệ với chúng tôi qua <a href="mailto:support@example.com">support@example.com</a>.</p>
            <p>Trân trọng, <br> Đội ngũ hỗ trợ khách hàng</p>
            <p><strong>Vui lòng gia hạn để tiếp tục sử dụng dịch vụ.</strong></p>
        </div>
    </div>
</body>
</html>
