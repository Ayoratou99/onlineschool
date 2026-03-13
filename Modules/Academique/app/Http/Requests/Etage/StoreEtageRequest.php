<?php

namespace Modules\Academique\Http\Requests\Etage;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Etage;

class StoreEtageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Etage::class);
    }

    public function rules(): array
    {
        return [
            'batiment_id' => ['required', 'uuid', 'exists:batiments,id'],
            'numero' => ['required', 'integer', 'min:0'],
            'libelle' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
