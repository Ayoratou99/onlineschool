<?php

namespace Modules\Parametrage\Http\Requests\AnneeAcademique;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Parametrage\Models\AnneeAcademique;

class StoreAnneeAcademiqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', AnneeAcademique::class);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:annees_academiques,code'],
            'libelle' => ['required', 'string', 'max:100'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
            'is_active' => ['boolean'],
            'created_by' => [
                'required',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if (! app(\App\Contracts\UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('created_by') && $this->user()) {
            $this->merge(['created_by' => $this->user()->id]);
        }
    }
}
