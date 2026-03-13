<?php

namespace Modules\Tenant\Documentation\Swagger;

use OpenApi\Attributes as OA;

abstract class TenantSwagger
{
    #[OA\Get(
        path: '/api/v1/tenant',
        summary: 'List tenants',
        description: 'List all tenants (admin only). Returns paginated list with domains.',
        tags: ['Tenants'],
        parameters: [
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15), description: 'Items per page'),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Page number'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 403, description: 'Forbidden - admin role required'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function index(): void;

    #[OA\Get(
        path: '/api/v1/tenant/{tenant}',
        summary: 'Show tenant',
        description: 'Get a single tenant by id (admin only).',
        tags: ['Tenants'],
        parameters: [
            new OA\Parameter(name: 'tenant', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Tenant identifier (id)'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success'),
            new OA\Response(response: 403, description: 'Forbidden - admin role required'),
            new OA\Response(response: 404, description: 'Tenant not found'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function show(): void;

    #[OA\Post(
        path: '/api/v1/tenant',
        summary: 'Create tenant',
        description: 'Create a new tenant with domains (admin only). Database and migrations are run automatically.',
        tags: ['Tenants'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id', 'domains'],
                properties: [
                    new OA\Property(property: 'id', type: 'string', maxLength: 255, description: 'Unique tenant identifier'),
                    new OA\Property(property: 'data', type: 'object', nullable: true, description: 'Optional JSON data to store on the tenant'),
                    new OA\Property(property: 'domains', type: 'array', items: new OA\Items(type: 'string'), description: 'At least one domain (e.g. ["foo.localhost"])'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Tenant created'),
            new OA\Response(response: 403, description: 'Forbidden - admin role required'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function store(): void;

    #[OA\Put(
        path: '/api/v1/tenant/{tenant}',
        summary: 'Update tenant',
        description: 'Update tenant data and/or domains (admin only).',
        tags: ['Tenants'],
        parameters: [
            new OA\Parameter(name: 'tenant', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Tenant identifier (id)'),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'data', type: 'object', nullable: true, description: 'JSON data to merge into tenant data'),
                    new OA\Property(property: 'domains', type: 'array', items: new OA\Items(type: 'string'), description: 'Replace all domains with this list'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Tenant updated'),
            new OA\Response(response: 403, description: 'Forbidden - admin role required'),
            new OA\Response(response: 404, description: 'Tenant not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function update(): void;

    #[OA\Delete(
        path: '/api/v1/tenant/{tenant}',
        summary: 'Delete tenant',
        description: 'Delete a tenant and its database (admin only).',
        tags: ['Tenants'],
        parameters: [
            new OA\Parameter(name: 'tenant', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Tenant identifier (id)'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Tenant deleted'),
            new OA\Response(response: 403, description: 'Forbidden - admin role required'),
            new OA\Response(response: 404, description: 'Tenant not found'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function destroy(): void;

    #[OA\Post(
        path: '/api/v1/tenant/{tenant}/clean',
        summary: 'Clean tenant (run migrations)',
        description: 'Run tenant database migrations (admin only). Use to ensure tenant DB schema is up to date.',
        tags: ['Tenants'],
        parameters: [
            new OA\Parameter(name: 'tenant', in: 'path', required: true, schema: new OA\Schema(type: 'string'), description: 'Tenant identifier (id)'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Migrations run successfully'),
            new OA\Response(response: 403, description: 'Forbidden - admin role required'),
            new OA\Response(response: 404, description: 'Tenant not found'),
        ],
        security: [['bearerAuth' => []]]
    )]
    abstract public function clean(): void;
}
