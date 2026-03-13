<?php

namespace Modules\Academique\Http\Requests\Cycle;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Cycle;

class StoreCycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Cycle::class);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:cycles,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'niveau_bac_requis' => ['nullable', 'string', 'max:100'],
            'duree_annees' => ['integer', 'min:1', 'max:10'],
            'credits_total' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
