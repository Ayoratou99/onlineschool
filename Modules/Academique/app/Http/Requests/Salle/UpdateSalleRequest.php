<?php

namespace Modules\Academique\Http\Requests\Salle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Salle;

class UpdateSalleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('salle'));
    }

    public function rules(): array
    {
        $id = $this->route('salle')?->id;
        return [
            'batiment_id' => ['sometimes', 'uuid', 'exists:batiments,id'],
            'etage_id' => ['sometimes', 'uuid', 'exists:etages,id'],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('salles', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'capacite' => ['nullable', 'integer', 'min:0'],
            'has_projecteur' => ['sometimes', 'boolean'],
            'has_climatisation' => ['sometimes', 'boolean'],
            'has_tableau_blanc' => ['sometimes', 'boolean'],
            'has_internet' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
