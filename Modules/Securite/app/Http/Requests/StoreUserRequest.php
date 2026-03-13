<?php

namespace Modules\Securite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Securite\Models\User;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'state' => 'sometimes|in:ACTIVE,BLOCKED',
            'two_factor_enabled' => 'sometimes|boolean',
            'roles' => 'sometimes|array',
            'roles.*' => 'uuid|exists:roles,id',
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }


    protected function prepareForValidation()
    {
        if ($this->has('roles') && is_string($this->roles)) {
            $this->merge([
                'roles' => explode(',', $this->roles),
            ]);
        }
    }

}
