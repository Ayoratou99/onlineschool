<?php

namespace Modules\Academique\Http\Requests\UniteEnseignement;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\UniteEnseignement;

class StoreUniteEnseignementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', UniteEnseignement::class);
    }

    public function rules(): array
    {
        return [
            'semestre_id' => ['required', 'uuid', 'exists:semestres,id'],
            'code' => ['required', 'string', 'max:50', 'unique:unites_enseignement,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'credits' => ['nullable', 'numeric', 'min:0'],
            'coefficient' => ['nullable', 'numeric', 'min:0'],
            'est_capitalisable' => ['boolean'],
            'est_compensable' => ['boolean'],
            'note_minimale' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
