<?php

namespace Modules\Academique\Http\Requests\MatiereEnseignant;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\MatiereEnseignant;

class UpdateMatiereEnseignantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('matiere_enseignant'));
    }

    public function rules(): array
    {
        return [
            'matiere_id' => ['sometimes', 'uuid', 'exists:matieres,id'],
            'enseignant_id' => [
                'sometimes',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if (! app(UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
            'annee_academique_id' => ['sometimes', 'uuid', 'exists:annees_academiques,id'],
            'groupe_id' => ['nullable', 'uuid', 'exists:groupes,id'],
            'type_seance' => ['nullable', 'string', 'max:50'],
            'is_principal' => ['sometimes', 'boolean'],
        ];
    }
}
