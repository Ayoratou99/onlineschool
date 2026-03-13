<?php

namespace Modules\Tenant\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Tenant\Services\AdminAuthService;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $token = $this->authService()->login($request->only('email', 'password'));
        return $this->respondWithAdminToken($token);
    }

    public function refresh(): JsonResponse
    {
        $token = $this->authService()->refresh();
        return $this->respondWithAdminToken($token);
    }

    public function logout(): JsonResponse
    {
        $this->authService()->logout();
        return $this->sendResponse([], 'FUIP_200');
    }

    public function me(): JsonResponse
    {
        $admin = auth()->guard('admin')->user();
        return $this->sendResponse($admin, 'FUIP_101');
    }

    protected function authService(): AdminAuthService
    {
        return app(AdminAuthService::class);
    }

    protected function respondWithAdminToken(string $token): JsonResponse
    {
        $guard = auth()->guard('admin');
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()->getTTL() * 60,
            'admin' => $guard->user(),
        ]);
    }
}
