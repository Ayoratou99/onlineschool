<?php

namespace Modules\Academique\Http\Requests\Batiment;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Batiment;

class StoreBatimentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Batiment::class);
    }

    public function rules(): array
    {
        return [
            'etablissement_id' => ['required', 'uuid', 'exists:etablissements,id'],
            'code' => ['required', 'string', 'max:50', 'unique:batiments,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
