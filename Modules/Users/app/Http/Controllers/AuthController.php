<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Users\Exceptions\KeycloakAuthenticationException;
use Modules\Users\Exceptions\KeycloakConnectionException;
use Modules\Users\Http\Requests\AuthLoginRequest;
use Modules\Users\Services\KeycloakService;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(KeycloakService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: '/api/user/auth/login',
        tags: ['Authentication'],
        summary: 'Login',
        description: 'Login a user with email and password',
        operationId: 'login',
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/x-www-form-urlencoded',
                    schema: new OA\Schema(
                        required: ['email', 'password'],
                        properties: [
                            new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                            new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                        ]
                    )
                ),
                new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(
                        required: ['email', 'password'],
                        properties: [
                            new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                            new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'user_uuid', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
                        new OA\Property(property: 'token', type: 'string', example: 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJ...'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid credentials',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid credentials or authentication failed.'),
                        new OA\Property(property: 'error', type: 'string', example: 'authentication_failed'),
                    ]
                )
            ),
            new OA\Response(
                response: 503,
                description: 'Service unavailable',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unable to connect to Keycloak server.'),
                        new OA\Property(property: 'error', type: 'string', example: 'service_unavailable'),
                    ]
                )
            ),
        ]
    )]
    public function login(AuthLoginRequest $request): JsonResponse
    {
        try {
            $authResponse = $this->authService->login($request->email, $request->password);

            return response()->json($authResponse->toArray(), 200);
        } catch (KeycloakAuthenticationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => 'authentication_failed',
            ], $e->getCode() ?: 401);
        } catch (KeycloakConnectionException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => 'service_unavailable',
            ], $e->getCode() ?: 503);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
                'error' => 'internal_server_error',
            ], 500);
        }
    }
}
