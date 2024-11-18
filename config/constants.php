<?php

return [

    'OTTCONTENTPREMIUM' => 'Premium',
    'OTTCONTENTFREE' => 'Free',

    'OTTCONTENTSTATUS' => [
        'OTTCONTENTPUBLISHED' => 'Published',
        'OTTCONTENTPENDING' => 'Pending',
        'OTTCONTENTHOLD' => 'Hold',
    ],
    'CATEGORYSTATUS' => [
        'PUBLISHED' => 'Published',
        'PENDING' => 'Pending',
        'HOLD' => 'Hold',
    ],
    'S3_PATH' => 'assets/',
    'CLOUDFRONT_URL' => 'https://d2xp19kujrzsg1.cloudfront.net/',
    'ENCODING_KEY' => env("ENCODING_KEY",''),
    'ENCODING_PASSWORD' => env("ENCODING_PASSWORD","encodingdurbar"),
    'ORIGIN_URL' => env('ORIGIN_URL', 'https://vod-1.dtlbackstage.com/Representable-Data/'),
    'DO_SPACES_PUBLIC' => env('DO_SPACES_PUBLIC','https://static.durbar.live/'),
    'PAYMENT_GATEWAY_URL' => env('PAYMENT_GATEWAY_URL','https://payment.durbar.live/'),
    'PAYMENT_GATEWAY_TOKEN' => env('PAYMENT_GATEWAY_TOKEN','Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTY1OTI1MDM3MiwibmJmIjoxNjU5MjUwMzcyLCJqdGkiOiI4VmRYcUZneUt2UEtOWnhXIiwic3ViIjoxLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.7jHl3arpovEmxWksO8oMQrGze6g2H95FeRqChUfnTIw'),
    'APP_CACHING_TIME_IN_MINUTE' => env('APP_CACHING_TIME_IN_MINUTE', 30),
];
