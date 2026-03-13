<?php

namespace Modules\Securite\Services;

use App\Jobs\SendAninfPushEmailJob;
use App\Services\BaseService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Securite\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService extends BaseService
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function confirmEmail(string $token, string $email, string $password): User
    {
        $cacheKey = 'email_verify_' . $token;
        $userId = Cache::get($cacheKey);
        if (!$userId) {
            throw new UnauthorizedHttpException('JWT', 'Invalid or expired verification token');
        }
        $user = User::findOrFail($userId);
        if ($user->email !== $email) {
            throw new UnauthorizedHttpException('JWT', 'Email does not match verification token');
        }
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->password = $password;
        $user->save();
        Cache::forget($cacheKey);
        return $user;
    }

    /**
     * @return string|array{requires_2fa: true, user_id: string}
     */
    public function login(array $credentials): string|array
    {
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            Event::dispatch(new Failed('api', null, $credentials));
            throw new UnauthorizedHttpException('JWT', 'Invalid credentials');
        }

        $user = Auth::guard('api')->user();

        if ($user->state === 'BLOCKED') {
            Auth::guard('api')->logout();
            throw new AccessDeniedHttpException('Account Blocked');
        }

        if ($user->two_factor_enabled) {
            Auth::guard('api')->logout();

            $tempToken = Str::random(64);
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $ttlMinutes = config('aninfpush.2fa_code.code_ttl_minutes', 10);
            Cache::put('2fa_temp_' . $tempToken, [
                'user_id' => $user->id,
                'code' => $code,
            ], now()->addMinutes($ttlMinutes));

            $payload = [
                'recipient_email' => $user->email,
                'template_identifier' => config('aninfpush.2fa_code.template_identifier', '2fa-code'),
                'from_email' => config('mail.from.address'),
                'variables' => [
                    'name' => trim($user->nom . ' ' . ($user->prenom ?? '')),
                    'code' => $code,
                ],
            ];
            try {
                SendAninfPushEmailJob::dispatch($payload);
            } catch (\Throwable $e) {
                Log::warning('AuthService: failed to dispatch 2FA code email', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            return [
                'requires_2fa' => true,
                'user_id' => $user->id,
                'temp_2fa_token' => $tempToken,
            ];
        }

        Event::dispatch(new Login('api', $user, false));

        return $token;
    }

    public function refresh(): string
    {
        $newToken = Auth::guard('api')->refresh();
        Auth::guard('api')->setToken($newToken)->authenticate();
        $user = Auth::guard('api')->user();

        if ($user->state === 'BLOCKED') {
            throw new AccessDeniedHttpException('Account Blocked');
        }

        return $newToken;
    }

    public function logout(): void
    {
        $user = Auth::guard('api')->user();
        Auth::guard('api')->logout();
        if ($user) {
            Event::dispatch(new Logout('api', $user));
        }
    }


    public function verify2fa(string $otp, ?string $userId = null, ?string $temp2faToken = null): string|array
    {
        if ($temp2faToken !== null && $temp2faToken !== '') {
            return $this->verify2faEmailCode($temp2faToken, $otp);
        }

        if ($userId === null || $userId === '') {
            return [
                'app_code' => 'FUIP_422',
                'detail' => 'user_id or temp_2fa_token is required',
                'http_code' => 422,
            ];
        }

        return $this->verify2faGoogleTotp($userId, $otp);
    }

    /**
     * Verify the 6-digit code sent by email (stored in cache with temp token).
     */
    private function verify2faEmailCode(string $temp2faToken, string $otp): string|array
    {
        $cacheKey = '2fa_temp_' . $temp2faToken;
        $payload = Cache::get($cacheKey);
        if (!$payload || !isset($payload['user_id'], $payload['code'])) {
            return [
                'app_code' => 'FUIP_401',
                'detail' => 'Invalid or expired 2FA token. Request a new code by logging in again.',
                'http_code' => 401,
            ];
        }

        if (!hash_equals($payload['code'], $otp)) {
            return [
                'app_code' => 'FUIP_401',
                'detail' => 'Invalid OTP',
                'http_code' => 401,
            ];
        }

        $user = User::find($payload['user_id']);
        if (!$user) {
            Cache::forget($cacheKey);
            return [
                'app_code' => 'FUIP_404',
                'detail' => 'User not found',
                'http_code' => 404,
            ];
        }

        if ($user->state === 'BLOCKED') {
            Cache::forget($cacheKey);
            return [
                'app_code' => 'FUIP_403',
                'detail' => 'Account Blocked',
                'http_code' => 403,
            ];
        }

        Cache::forget($cacheKey);
        $token = Auth::guard('api')->login($user);
        Event::dispatch(new Login('api', $user, false));
        return $token;
    }


    private function verify2faGoogleTotp(string $userId, string $otp): string|array
    {
        $user = User::findOrFail($userId);

        if (!$user->two_factor_enabled || !$user->two_factor_secret) {
            return [
                'app_code' => 'FUIP_422',
                'detail' => '2FA is not enabled for this account',
                'http_code' => 422,
            ];
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        if (!$google2fa->verifyKey($secret, $otp)) {
            return [
                'app_code' => 'FUIP_401',
                'detail' => 'Invalid OTP',
                'http_code' => 401,
            ];
        }

        if ($user->state === 'BLOCKED') {
            return [
                'app_code' => 'FUIP_403',
                'detail' => 'Account Blocked',
                'http_code' => 403,
            ];
        }

        $token = Auth::guard('api')->login($user);
        Event::dispatch(new Login('api', $user, false));

        return $token;
    }


    public function resetPassword(string $email, ?string $tokenForTesting = null): void
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return;
        }

        $token = $tokenForTesting ?? Str::random(64);
        $ttl = config('aninfpush.email_verification.token_ttl_minutes', 60 * 24);
        Cache::put('email_verify_' . $token, $user->id, now()->addMinutes($ttl));

        $frontendUrl = rtrim(config('aninfpush.email_verification.frontend_url'), '/');
        $url = $frontendUrl
            ? $frontendUrl . '?token=' . urlencode($token) . '&email=' . urlencode($user->email)
            : '';

        $payload = [
            'recipient_email' => $user->email,
            'template_identifier' => config('aninfpush.email_verification.template_identifier', 'email-verification'),
            'from_email' => config('mail.from.address'),
            'variables' => [
                'name' => trim($user->nom . ' ' . ($user->prenom ?? '')),
                'url' => $url,
            ],
        ];

        try {
            SendAninfPushEmailJob::dispatch($payload);
        } catch (\Throwable $e) {
            Log::warning('AuthService::resetPassword: failed to dispatch email', ['email' => $email, 'error' => $e->getMessage()]);
        }
    }
}