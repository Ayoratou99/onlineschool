<?php

namespace Modules\Parametrage\Http\Requests\AnneeAcademique;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Parametrage\Models\AnneeAcademique;

class UpdateAnneeAcademiqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('annee_academique'));
    }

    public function rules(): array
    {
        $id = $this->route('annee_academique')?->id;
        return [
            'code' => ['sometimes', 'string', 'max:20', Rule::unique('annees_academiques', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:100'],
            'date_debut' => ['sometimes', 'date'],
            'date_fin' => ['sometimes', 'date', 'after_or_equal:date_debut'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
