<?php

namespace Modules\Academique\Http\Requests\Groupe;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Groupe;

class StoreGroupeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Groupe::class);
    }

    public function rules(): array
    {
        return [
            'niveau_id' => ['required', 'uuid', 'exists:niveaux,id'],
            'annee_academique_id' => ['required', 'uuid', 'exists:annees_academiques,id'],
            'code' => ['required', 'string', 'max:50', 'unique:groupes,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'effectif_max' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
