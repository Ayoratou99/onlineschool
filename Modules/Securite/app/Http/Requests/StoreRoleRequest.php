<?php

namespace Modules\Securite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Securite\Models\Role;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Role::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'state' => 'required|in:ACTIVE,BLOCKED',
            'permissions' => 'nullable|array',
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
