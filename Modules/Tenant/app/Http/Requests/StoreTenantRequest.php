<?php

namespace Modules\Tenant\Http\Requests;

use Modules\Tenant\Models\Tenant;
use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Tenant::class);
    }

    public function rules(): array
    {
        return [
            'id' => 'required|string|max:255|unique:tenants,id',
            'data' => 'nullable|array',
            'domains' => 'required|array|min:1',
            'domains.*' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The tenant identifier is required.',
            'id.unique' => 'A tenant with this identifier already exists.',
            'domains.required' => 'At least one domain is required.',
        ];
    }
}
