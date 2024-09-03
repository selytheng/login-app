<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #96e6a1, #d4fc79);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #28a745;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
        }

        .otp-code {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            font-size: 24px;
            letter-spacing: 2px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your OTP Code</h1>
        <p>Use the following OTP to complete your registration:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>This OTP is valid for 10 minutes.</p>
    </div>
</body>
</html>
