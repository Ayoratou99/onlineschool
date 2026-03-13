<?php

namespace Modules\Academique\Http\Requests\Niveau;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Niveau;

class UpdateNiveauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('niveau'));
    }

    public function rules(): array
    {
        $id = $this->route('niveau')?->id;
        return [
            'filiere_id' => ['sometimes', 'uuid', 'exists:filieres,id'],
            'parcours_id' => ['sometimes', 'uuid', 'exists:parcours,id'],
            'annee_academique_id' => ['sometimes', 'uuid', 'exists:annees_academiques,id'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('niveaux', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'credits_requis' => ['nullable', 'integer', 'min:0'],
            'effectif_max' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
