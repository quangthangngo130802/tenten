<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo tài khoản</title>
    <style>
        /* Đảm bảo padding và font size hợp lý trên mobile */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            margin: 0 0 10px;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            margin: 20px auto;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .footer {
            background-color: #f4f4f4;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 10px;
        }

        /* Media query cho thiết bị nhỏ hơn 480px */
        @media screen and (max-width: 480px) {
            .header {
                font-size: 20px;
                padding: 15px;
            }
            .content {
                padding: 15px;
            }
            .btn {
                font-size: 14px;
                padding: 10px 15px;
            }
            .footer {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <table class="container">
        <tr>
            <td class="header">
                Thông báo tài khoản
            </td>
        </tr>
        <tr>
            <td class="content">
                <p>Xin chào,</p>
                <p>Chúng tôi đã tạo tài khoản mới cho bạn hoặc bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>
                <p><strong>Email đăng nhập:</strong> <span style="color: #4CAF50;">{{ $data['username'] }}</span></p>
                <p><strong>Mật khẩu mới:</strong> <span style="color: #4CAF50;">{{ $data['password'] }}</span></p>
                <p>Để thay đổi mật khẩu hoặc đăng nhập lần đầu, vui lòng nhấp vào nút bên dưới:</p>
                <div style="text-align: center;">
                    <a href="{{ $data['reset_link'] }}" class="btn">Đặt lại mật khẩu</a>
                </div>
                <p style="font-size: 14px; color: #666;">Nếu bạn không yêu cầu tạo tài khoản hoặc đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
                <p>Trân trọng,<br>Đội ngũ Your Application</p>
            </td>
        </tr>
        <tr>
            <td class="footer">
                © 2024 Your Application. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
