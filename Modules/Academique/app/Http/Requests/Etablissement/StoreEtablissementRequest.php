<?php

namespace Modules\Academique\Http\Requests\Etablissement;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Etablissement;

class StoreEtablissementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Etablissement::class);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:etablissements,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
