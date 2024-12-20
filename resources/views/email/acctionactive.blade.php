<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kích hoạt tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: #007BFF;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            color: #333333;
        }
        .email-body p {
            line-height: 1.6;
            margin: 15px 0;
        }
        .email-body a {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .email-body a:hover {
            background-color: #0056b3;
        }
        .email-footer {
            background: #f4f4f4;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #666666;
        }
        .email-footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Kích hoạt tài khoản</h1>
        </div>


        <div class="email-body">
            <h2>Chào {{ $data['name'] }},</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại
                {{-- <strong>{{ config('app.name') }}</strong>. --}}
            </p>
            <p>Vui lòng nhấp vào liên kết bên dưới để kích hoạt tài khoản của bạn: <span>{{ $data['username'] }}</span></p>
            <a href="{{ $data['activationUrl'] }}">Kích hoạt tài khoản</a>
            <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>Trân trọng
                {{-- ,<br>Đội ngũ {{ config('app.name') }}</p> --}}
        </div>
    </div>
</body>
</html>
