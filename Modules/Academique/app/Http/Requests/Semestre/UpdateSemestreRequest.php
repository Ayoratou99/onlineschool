<?php

namespace Modules\Academique\Http\Requests\Semestre;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Semestre;

class UpdateSemestreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('semestre'));
    }

    public function rules(): array
    {
        $id = $this->route('semestre')?->id;
        return [
            'niveau_id' => ['sometimes', 'uuid', 'exists:niveaux,id'],
            'annee_academique_id' => ['sometimes', 'uuid', 'exists:annees_academiques,id'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('semestres', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'date_debut_examen' => ['nullable', 'date'],
            'date_fin_examen' => ['nullable', 'date', 'after_or_equal:date_debut_examen'],
            'is_locked' => ['sometimes', 'boolean'],
        ];
    }
}
