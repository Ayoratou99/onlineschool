<?php

return [
    'api' => [
        'base_url' => env('ANINFPUSH_API_URL', 'http://localhost:8095'),
        'client_id' => env('ANINFPUSH_CLIENT_ID'),
        'client_secret' => env('ANINFPUSH_CLIENT_SECRET'),
    ],
    'email_verification' => [
        'template_identifier' => env('ANINFPUSH_EMAIL_VERIFICATION_TEMPLATE', 'email-verification'),
        'token_ttl_minutes' => (int) env('ANINFPUSH_VERIFICATION_TOKEN_TTL', 60 * 24),
        'frontend_url' => env('ANINFPUSH_VERIFICATION_FRONTEND_URL', ''),
    ],

    '2fa_code' => [
        'template_identifier' => env('ANINFPUSH_2FA_CODE_TEMPLATE', '2fa-code'),
        'code_ttl_minutes' => (int) env('ANINFPUSH_2FA_CODE_TTL', 10),
    ],
];
