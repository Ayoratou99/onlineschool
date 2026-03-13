<?php

namespace Modules\Tenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatsTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', \Modules\Tenant\Models\Tenant::class);
    }

    public function rules(): array
    {
        return [];
    }
}
