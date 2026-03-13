<?php

namespace Modules\Securite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisableTwoFactorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user->hasRole('ADMIN')
            || $user->hasPermissionTo('REINITIALISER_2FA')
            || $user->hasPermissionTo('DESACTIVER_2FA');
    }

    public function rules(): array
    {
        return [
            'otp' => 'required|digits:6',
        ];
    }
}
