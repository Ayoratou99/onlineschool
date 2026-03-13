<?php

namespace Modules\Academique\Http\Requests\Salle;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Salle;

class StoreSalleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Salle::class);
    }

    public function rules(): array
    {
        return [
            'batiment_id' => ['required', 'uuid', 'exists:batiments,id'],
            'etage_id' => ['required', 'uuid', 'exists:etages,id'],
            'code' => ['required', 'string', 'max:50', 'unique:salles,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'capacite' => ['nullable', 'integer', 'min:0'],
            'has_projecteur' => ['boolean'],
            'has_climatisation' => ['boolean'],
            'has_tableau_blanc' => ['boolean'],
            'has_internet' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
