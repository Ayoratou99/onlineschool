<?php

namespace Modules\Tenant\Documentation\Swagger;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Admin Auth',
    description: 'Central admin authentication (JWT). Use these endpoints to manage tenant resources; separate from tenant-scoped user auth.'
)]
#[OA\Schema(
    schema: 'Admin',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '9d4e8f2a-1234-5678-90ab-cdef12345678'),
        new OA\Property(property: 'name', type: 'string', example: 'Super Admin'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@central.local'),
        new OA\Property(property: 'state', type: 'string', enum: ['ACTIVE', 'BLOCKED']),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
abstract class AdminAuthSwagger
{
    #[OA\Post(
        path: '/api/v1/admin/login',
        summary: 'Admin login',
        description: 'Authenticate as central admin. Returns JWT for admin guard (tenant CRUD, clean, etc.).',
        tags: ['Admin Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@central.local'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'access_token', type: 'string'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer'),
                        new OA\Property(property: 'admin', ref: '#/components/schemas/Admin'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid credentials'),
            new OA\Response(response: 403, description: 'Account blocked'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    abstract public function login(): JsonResponse;

    #[OA\Post(
        path: '/api/v1/admin/refresh',
        summary: 'Refresh admin token',
        description: 'Refresh JWT using current admin token. Requires Authorization: Bearer <token>.',
        tags: ['Admin Auth'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'New token',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'access_token', type: 'string'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer'),
                        new OA\Property(property: 'admin', ref: '#/components/schemas/Admin'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid or expired token'),
            new OA\Response(response: 403, description: 'Account blocked'),
        ]
    )]
    abstract public function refresh(): JsonResponse;

    #[OA\Post(
        path: '/api/v1/admin/logout',
        summary: 'Admin logout',
        description: 'Invalidate current admin JWT.',
        tags: ['Admin Auth'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logged out'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    abstract public function logout(): JsonResponse;

    #[OA\Get(
        path: '/api/v1/admin/me',
        summary: 'Current admin',
        description: 'Get current authenticated admin.',
        tags: ['Admin Auth'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Admin profile',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'app_code', type: 'string', example: 'FUIP_101'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Admin'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    abstract public function me(): JsonResponse;
}
