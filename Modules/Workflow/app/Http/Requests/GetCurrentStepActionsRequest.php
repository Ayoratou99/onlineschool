<?php

namespace Modules\Workflow\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCurrentStepActionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('workflow.viewInstance');
    }

    public function rules(): array
    {
        return [
            'userId'         => ['sometimes', 'nullable', 'string'],
            'roleFilter'     => ['sometimes', 'nullable', 'string', 'max:255'],
            'functionFilter'  => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
