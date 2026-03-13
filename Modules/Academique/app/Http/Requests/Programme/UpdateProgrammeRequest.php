<?php

namespace Modules\Academique\Http\Requests\Programme;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Programme;

class UpdateProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('programme'));
    }

    public function rules(): array
    {
        return [
            'niveau_id' => ['sometimes', 'uuid', 'exists:niveaux,id'],
            'annee_academique_id' => ['sometimes', 'uuid', 'exists:annees_academiques,id'],
            'version' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
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
