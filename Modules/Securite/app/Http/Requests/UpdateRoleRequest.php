<?php

namespace Modules\Securite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Securite\Models\Role;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = $this->route('role');
        return $role && $this->user()->can('update', $role);
    }

    public function rules(): array
    {
        $role = $this->route('role');
        $roleId = $role?->id;

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
            'description' => 'sometimes|nullable|string|max:500',
            'state' => 'sometimes|in:ACTIVE,BLOCKED',
            'permissions' => 'sometimes|nullable|array',
            'permissions.*' => 'uuid|exists:permissions,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('permissions') && is_string($this->permissions)) {
            $this->merge([
                'permissions' => array_filter(explode(',', $this->permissions)),
            ]);
        }
    }
}
