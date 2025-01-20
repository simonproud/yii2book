<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'bsVersion' => '5.x',
    'sms' => [
        'apiKey' => getenv('SMS_API_KEY'),
        'sender' => 'INFORM'
    ]
];
