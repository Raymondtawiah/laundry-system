<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Code</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #2563eb;
            margin: 20px 0;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Verification Code</h2>
        
        <p>Hello {{ $name }},</p>
        
        <p>Your verification code is:</p>
        
        <div class="code">{{ $code }}</div>
        
        <p>This code will expire in {{ $expires }}.</p>
        
        <div class="footer">
            <p>If you didn't request this code, please ignore this email.</p>
        </div>
    </div>
</body>
</html>
