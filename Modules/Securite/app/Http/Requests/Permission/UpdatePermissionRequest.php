<?php

namespace Modules\Securite\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Securite\Models\Permission;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('permission'));
    }

    public function rules(): array
    {
        $id = $this->route('permission')?->id;
        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('permissions', 'name')->ignore($id)],
            'description' => ['nullable', 'string', 'max:500'],
            'state' => ['sometimes', 'string', 'in:ACTIVE,BLOCKED'],
        ];
    }
}
