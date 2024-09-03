<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Platform</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #96e6a1, #d4fc79);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 36px;
            color: #28a745;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .signature {
            font-size: 18px;
            margin-top: 30px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, {{ $userName }}!</h1>
        <p>Thank you for registering on our platform. We are thrilled to have you with us.</p>
        <p>If you have any questions, feel free to reach out to our support team.</p>
        <p class="signature">Best Regards,<br>The Team</p>
    </div>
</body>
</html>
