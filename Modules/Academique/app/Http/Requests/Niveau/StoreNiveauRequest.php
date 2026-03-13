<?php

namespace Modules\Academique\Http\Requests\Niveau;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Niveau;

class StoreNiveauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Niveau::class);
    }

    public function rules(): array
    {
        return [
            'filiere_id' => ['required', 'uuid', 'exists:filieres,id'],
            'parcours_id' => ['required', 'uuid', 'exists:parcours,id'],
            'annee_academique_id' => ['required', 'uuid', 'exists:annees_academiques,id'],
            'code' => ['required', 'string', 'max:50', 'unique:niveaux,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'credits_requis' => ['nullable', 'integer', 'min:0'],
            'effectif_max' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
