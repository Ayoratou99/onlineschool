<?php

namespace Modules\Academique\Http\Requests\UniteEnseignement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\UniteEnseignement;

class UpdateUniteEnseignementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('unite_enseignement'));
    }

    public function rules(): array
    {
        $id = $this->route('unite_enseignement')?->id;
        return [
            'semestre_id' => ['sometimes', 'uuid', 'exists:semestres,id'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('unites_enseignement', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'credits' => ['nullable', 'numeric', 'min:0'],
            'coefficient' => ['nullable', 'numeric', 'min:0'],
            'est_capitalisable' => ['sometimes', 'boolean'],
            'est_compensable' => ['sometimes', 'boolean'],
            'note_minimale' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
