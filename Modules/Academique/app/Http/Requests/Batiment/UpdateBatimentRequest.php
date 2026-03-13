<?php

namespace Modules\Academique\Http\Requests\Batiment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Batiment;

class UpdateBatimentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('batiment'));
    }

    public function rules(): array
    {
        $id = $this->route('batiment')?->id;
        return [
            'etablissement_id' => ['sometimes', 'uuid', 'exists:etablissements,id'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('batiments', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
