<?php

namespace Modules\Parametrage\Http\Requests\BaremeMention;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Parametrage\Models\BaremeMention;

class StoreBaremeMentionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', BaremeMention::class);
    }

    public function rules(): array
    {
        return [
            'annee_academique_id' => ['required', 'uuid', 'exists:annees_academiques,id'],
            'mention' => ['required', 'string', 'max:50'],
            'bareme_min' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'bareme_max' => ['required', 'numeric', 'min:0', 'max:999.99', 'gte:bareme_min'],
            'ordre' => ['integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('ordre') && is_string($this->ordre)) {
            $this->merge(['ordre' => (int) $this->ordre]);
        }
    }
}
