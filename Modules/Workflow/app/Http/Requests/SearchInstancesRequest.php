<?php

namespace Modules\Workflow\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchInstancesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('workflow.searchInstances');
    }

    public function rules(): array
    {
        return [
            'statut' => ['sometimes', 'nullable', 'string', 'max:50'],
            'page'   => ['sometimes', 'integer', 'min:1'],
            'size'   => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
