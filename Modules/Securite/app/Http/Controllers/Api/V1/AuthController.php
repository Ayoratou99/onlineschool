<?php

namespace Modules\Securite\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Securite\Http\Requests\ConfirmEmailRequest;
use Modules\Securite\Http\Requests\ResetPasswordRequest;
use Modules\Securite\Http\Requests\Verify2faRequest;
use Modules\Securite\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(AuthService $service)
    {
        parent::__construct($service);
    }

    public function confirmEmail(ConfirmEmailRequest $request): JsonResponse
    {
        $user = $this->service->confirmEmail(
            $request->validated('token'),
            $request->validated('email'),
            $request->validated('password')
        );
        return $this->sendResponse($user, 'FUIP_200');
    }

    public function login(Request $request)
    {
        $result = $this->service->login($request->only('email', 'password'));
        if (is_array($result)) {
            return $this->sendResponse([
                'requires_2fa' => $result['requires_2fa'],
                'user_id' => $result['user_id'],
                'temp_2fa_token' => $result['temp_2fa_token'] ?? null,
            ], 'FUIP_2FA_REQUIRED');
        }

        return $this->respondWithToken($result);
    }

    public function refresh()
    {
        $newToken = $this->service->refresh();
        return $this->respondWithToken($newToken);
    }

    public function logout()
    {
        $this->service->logout();
        return $this->sendResponse([], 'FUIP_200');
    }

    public function verify2fa(Verify2faRequest $request): JsonResponse
    {
        $result = $this->service->verify2fa(
            $request->validated('otp'),
            $request->validated('user_id'),
            $request->validated('temp_2fa_token')
        );

        if (is_array($result)) {
            return $this->sendError($result['app_code'], ['detail' => $result['detail']], $result['http_code']);
        }

        return $this->respondWithToken($result);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $this->service->resetPassword($request->validated('email'));
        return $this->sendResponse([], 'FUIP_PASSWORD_RESET_REQUESTED');
    }
}