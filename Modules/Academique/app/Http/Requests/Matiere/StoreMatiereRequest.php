<?php

namespace Modules\Academique\Http\Requests\Matiere;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Matiere;

class StoreMatiereRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Matiere::class);
    }

    public function rules(): array
    {
        return [
            'ue_id' => ['required', 'uuid', 'exists:unites_enseignement,id'],
            'code' => ['required', 'string', 'max:50', 'unique:matieres,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'credits' => ['nullable', 'numeric', 'min:0'],
            'coefficient' => ['nullable', 'numeric', 'min:0'],
            'vh_cm' => ['nullable', 'integer', 'min:0'],
            'vh_td' => ['nullable', 'integer', 'min:0'],
            'vh_tp' => ['nullable', 'integer', 'min:0'],
            'est_compensable' => ['boolean'],
            'note_eliminatoire' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
