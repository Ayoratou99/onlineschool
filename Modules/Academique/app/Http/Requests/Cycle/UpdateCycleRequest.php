<?php

namespace Modules\Academique\Http\Requests\Cycle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Cycle;

class UpdateCycleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('cycle'));
    }

    public function rules(): array
    {
        $id = $this->route('cycle')?->id;
        return [
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('cycles', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'niveau_bac_requis' => ['nullable', 'string', 'max:100'],
            'duree_annees' => ['sometimes', 'integer', 'min:1', 'max:10'],
            'credits_total' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
