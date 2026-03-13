<?php

namespace Modules\Academique\Http\Requests\Programme;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Programme;

class StoreProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Programme::class);
    }

    public function rules(): array
    {
        return [
            'niveau_id' => ['required', 'uuid', 'exists:niveaux,id'],
            'annee_academique_id' => ['required', 'uuid', 'exists:annees_academiques,id'],
            'version' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'valide_par' => [
                'nullable',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if ($value !== null && ! app(UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
            'valide_le' => ['nullable', 'date'],
        ];
    }
}
