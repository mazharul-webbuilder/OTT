<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Verification Code</title>
    <style>
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
        .header {
            background-color: #0044cc;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
            text-align: left;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777777;
            border-radius: 0 0 5px 5px;
        }
        .button {
            background-color: #0044cc;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="https://www.dtlbackstage.com/assets/DurbarTechIcon.3883da84.svg" alt="Logo">
    </div>
    <div class="header">
        <h1>Newsletter</h1>
    </div>
    <div class="content">
        <p>{!! $data['email_body'] !!}</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{config('app.name')}}. All rights reserved.</p>
        <p>If you no longer wish to receive these emails, you can <a href="{{ $data['unsubscribe_page'] }}">unsubscribe here</a>.</p>
    </div>
</div>
</body>
</html>
