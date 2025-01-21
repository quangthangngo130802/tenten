<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thông tin tài khoản mua hàng</title>
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
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333333;
        }
        p {
            color: #555555;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .email-content {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Cảm ơn bạn đã Hosting!</h1>
        <p>Chào <strong>{{ $data['name'] ?? 'Khách hàng' }}</strong>,</p>
        <p>Chúng tôi rất vui khi bạn đã chọn mua sắm tại cửa hàng của chúng tôi.</p>
        <p>Dưới đây là thông tin tài khoản của bạn:</p>
        <div>Email: <span>{{ $data['email'] ?? 'Không có thông tin' }}</span></div>
        <div class="email-content">
            {{-- Xử lý nội dung --}}
            @if (!empty($data['content']))
                {!! $data['content'] !!}
            @else
                <p>Không có nội dung bổ sung.</p>
            @endif
        </div>
        <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với đội ngũ hỗ trợ của chúng tôi qua email hoặc điện thoại.</p>
        <p>Trân trọng,<br>Đội ngũ cửa hàng của bạn</p>
    </div>
</body>
</html>
