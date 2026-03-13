<?php

namespace Modules\Academique\Http\Requests\Parcours;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Parcours;

class UpdateParcoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('parcour'));
    }

    public function rules(): array
    {
        $id = $this->route('parcour')?->id;
        return [
            'filiere_id' => ['sometimes', 'uuid', 'exists:filieres,id'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('parcours', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
