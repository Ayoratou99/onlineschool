<?php

namespace Modules\Academique\Http\Requests\Etage;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Etage;

class UpdateEtageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('etage'));
    }

    public function rules(): array
    {
        return [
            'batiment_id' => ['sometimes', 'uuid', 'exists:batiments,id'],
            'numero' => ['sometimes', 'integer', 'min:0'],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
