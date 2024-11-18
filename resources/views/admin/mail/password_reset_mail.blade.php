<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Verification Code</title>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 20px;
        }
        .note {
            color: #666666;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="https://www.dtlbackstage.com/assets/DurbarTechIcon.3883da84.svg" alt="Logo">
    </div>
    <div class="content">
        <p>Hello {{ $admin->name }},</p>
        <p>Your password reset verification code is:</p>
        <p class="code">{{ $admin->verification_code }}</p>
        <p class="note">This code is valid for a limited time. Please use it to reset your password.</p>
    </div>
</div>
</body>
</html>
