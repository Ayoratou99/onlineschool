<?php

namespace Modules\Tenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', \Modules\Tenant\Models\Tenant::class);
    }

    public function rules(): array
    {
        return [
            'per_page'  => ['sometimes', 'integer', 'min:1', 'max:100'],
            'with_stats' => ['sometimes', 'boolean'],
        ];
    }
}
