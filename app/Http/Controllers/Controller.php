<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'AninfPush API Documentation',
    description: 'API microservice for managing multi-channel messaging (Email, SMS, WhatsApp) with Keycloak authentication',
    contact: new OA\Contact(
        email: 'support@aninfpush.com'
    ),
    license: new OA\License(
        name: 'Proprietary',
        url: 'https://aninfpush.com/license'
    )
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Local Development Server'
)]
#[OA\Server(
    url: 'https://api.yourdomain.com',
    description: 'Production Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Enter Keycloak JWT token'
)]
abstract class Controller {}
