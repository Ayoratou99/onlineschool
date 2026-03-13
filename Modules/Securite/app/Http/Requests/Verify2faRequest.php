<?php

namespace Modules\Securite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Verify2faRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required_without:temp_2fa_token',
                'uuid',
                'exists:users,id',
            ],
            'temp_2fa_token' => ['required_without:user_id', 'string', 'min:1'],
            'otp' => 'required|digits:6',
        ];
    }
}
