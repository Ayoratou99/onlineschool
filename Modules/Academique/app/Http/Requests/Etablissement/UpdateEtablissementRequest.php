<?php

namespace Modules\Academique\Http\Requests\Etablissement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Etablissement;

class UpdateEtablissementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('etablissement'));
    }

    public function rules(): array
    {
        $id = $this->route('etablissement')?->id;
        return [
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('etablissements', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
