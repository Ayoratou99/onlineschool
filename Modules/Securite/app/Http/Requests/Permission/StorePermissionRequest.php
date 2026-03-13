<?php

namespace Modules\Securite\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Securite\Models\Permission;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Permission::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'state' => ['required', 'string', 'in:ACTIVE,BLOCKED'],
        ];
    }
}
