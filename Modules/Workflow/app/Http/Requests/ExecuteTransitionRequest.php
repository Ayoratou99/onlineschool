<?php

namespace Modules\Workflow\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExecuteTransitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('workflow.executeTransition') ?? false;
    }

    public function rules(): array
    {
        return [
            'action'     => ['required', 'string'],
            'userId'     => ['required', 'string'],
            'commentaire' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'L\'action est requise.',
            'userId.required' => 'L\'identifiant utilisateur est requis.',
        ];
    }
}
