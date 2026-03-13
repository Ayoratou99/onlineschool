<?php

namespace Modules\Securite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmTwoFactorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }
        return $user->hasRole('ADMIN')
            || $user->hasPermissionTo('RESET_2FA_UTILISATEUR')
            || true;
    }

    public function rules(): array
    {
        return [
            'otp' => 'required|digits:6',
        ];
    }
}
