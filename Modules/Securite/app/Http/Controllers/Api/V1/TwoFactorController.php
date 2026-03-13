<?php

namespace Modules\Securite\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Securite\Http\Requests\ConfirmTwoFactorRequest;
use Modules\Securite\Http\Requests\DisableTwoFactorRequest;
use Modules\Securite\Models\User;
use Modules\Securite\Services\AuthService;
use Modules\Securite\Services\TwoFactorService;

class TwoFactorController extends Controller
{
    public function __construct(
        AuthService $service,
        protected TwoFactorService $twoFactorService
    ) {
        parent::__construct($service);
    }

    public function enable(): JsonResponse
    {
        $data = $this->twoFactorService->enable(auth()->user());

        return $this->sendResponse([
            'qr_code' => $data['qr_code'],
            'secret' => $data['secret'],
        ], 'FUIP_200');
    }

    public function confirm(ConfirmTwoFactorRequest $request): JsonResponse
    {
        $this->twoFactorService->confirm(auth()->user(), $request->validated('otp'));
        return $this->sendResponse(['message' => '2FA enabled successfully'], 'FUIP_200');
    }

    public function disable(DisableTwoFactorRequest $request): JsonResponse
    {
        $this->twoFactorService->disable(auth()->user(), $request->validated('otp'));
        return $this->sendResponse(['message' => '2FA disabled successfully'], 'FUIP_200');
    }

    public function reset(User $user): JsonResponse
    {
        $this->authorize('reset2fa', $user);

        $this->twoFactorService->reset($user);

        return $this->sendResponse(['message' => '2FA has been reset for this user'], 'FUIP_200');
    }
}
