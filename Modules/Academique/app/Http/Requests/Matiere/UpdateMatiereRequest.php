<?php

namespace Modules\Academique\Http\Requests\Matiere;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Matiere;

class UpdateMatiereRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('matiere'));
    }

    public function rules(): array
    {
        $id = $this->route('matiere')?->id;
        return [
            'ue_id' => ['sometimes', 'uuid', 'exists:unites_enseignement,id'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('matieres', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'credits' => ['nullable', 'numeric', 'min:0'],
            'coefficient' => ['nullable', 'numeric', 'min:0'],
            'vh_cm' => ['nullable', 'integer', 'min:0'],
            'vh_td' => ['nullable', 'integer', 'min:0'],
            'vh_tp' => ['nullable', 'integer', 'min:0'],
            'est_compensable' => ['sometimes', 'boolean'],
            'note_eliminatoire' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
