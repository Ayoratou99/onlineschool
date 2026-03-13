<?php

namespace Modules\Tenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view', $this->route('tenant'));
    }

    public function rules(): array
    {
        return [
            'with_stats' => ['sometimes', 'boolean'],
        ];
    }
}
