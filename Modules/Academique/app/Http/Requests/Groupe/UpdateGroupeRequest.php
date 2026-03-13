<?php

namespace Modules\Academique\Http\Requests\Groupe;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Groupe;

class UpdateGroupeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('groupe'));
    }

    public function rules(): array
    {
        $id = $this->route('groupe')?->id;
        return [
            'niveau_id' => ['sometimes', 'uuid', 'exists:niveaux,id'],
            'annee_academique_id' => ['sometimes', 'uuid', 'exists:annees_academiques,id'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('groupes', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'effectif_max' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
