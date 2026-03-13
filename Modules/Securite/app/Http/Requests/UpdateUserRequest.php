<?php

namespace Modules\Securite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Securite\Models\User;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        return $user && $this->user()->can('update', $user);
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user?->id;

        return [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|nullable|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'state' => 'sometimes|in:ACTIVE,BLOCKED',
            'two_factor_enabled' => 'sometimes|boolean',
            'roles' => 'sometimes|nullable|array',
            'roles.*' => 'uuid|exists:roles,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('roles') && is_string($this->roles)) {
            $this->merge([
                'roles' => array_filter(explode(',', $this->roles)),
            ]);
        }
    }
}
