<?php

return [
    'name' => 'Workflow',
    'api' => [
        'base_url'  => env('WORKFLOW_API_URL'),
        'app_token' => env('WORKFLOW_APP_TOKEN'),
        'username'  => env('WORKFLOW_API_USERNAME'),
        'password'  => env('WORKFLOW_API_PASSWORD'),
    ],
];