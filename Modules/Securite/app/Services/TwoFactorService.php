<?php

namespace Modules\Securite\Services;

use Modules\Securite\Models\User;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{
    public function enable(User $user): array
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->two_factor_secret = encrypt($secret);
        $user->two_factor_enabled = false;
        $user->save();

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return [
            'qr_code' => $qrCodeUrl,
            'secret' => $secret,
        ];
    }

    public function confirm(User $user, string $otp): void
    {
        if (!$user->two_factor_secret) {
            throw new \InvalidArgumentException('2FA setup not started');
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        if (!$google2fa->verifyKey($secret, $otp)) {
            throw new \InvalidArgumentException('Invalid OTP');
        }

        $user->two_factor_enabled = true;
        $user->save();
    }

    public function disable(User $user, string $otp): void
    {
        if (!$user->two_factor_enabled || !$user->two_factor_secret) {
            throw new \InvalidArgumentException('2FA is not enabled');
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->two_factor_secret);

        if (!$google2fa->verifyKey($secret, $otp)) {
            throw new \InvalidArgumentException('Invalid OTP');
        }

        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->save();
    }

    public function reset(User $user): void
    {
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->save();
    }
}
