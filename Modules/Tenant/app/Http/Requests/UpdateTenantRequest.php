<?php

namespace Modules\Tenant\Http\Requests;

use Modules\Tenant\Models\Tenant;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tenant'));
    }

    public function rules(): array
    {
        $tenant = $this->route('tenant');

        return [
            'data' => 'nullable|array',
            'domains' => 'sometimes|array|min:1',
            'domains.*' => 'required_with:domains|string|max:255',
        ];
    }
}
