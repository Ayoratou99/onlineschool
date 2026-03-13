<?php

namespace Modules\Academique\Http\Requests\Semestre;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Semestre;

class StoreSemestreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Semestre::class);
    }

    public function rules(): array
    {
        return [
            'niveau_id' => ['required', 'uuid', 'exists:niveaux,id'],
            'annee_academique_id' => ['required', 'uuid', 'exists:annees_academiques,id'],
            'code' => ['required', 'string', 'max:50', 'unique:semestres,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'date_debut_examen' => ['nullable', 'date'],
            'date_fin_examen' => ['nullable', 'date', 'after_or_equal:date_debut_examen'],
            'is_locked' => ['boolean'],
        ];
    }
}
