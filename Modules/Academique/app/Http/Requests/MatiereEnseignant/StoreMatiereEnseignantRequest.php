<?php

namespace Modules\Academique\Http\Requests\MatiereEnseignant;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\MatiereEnseignant;

class StoreMatiereEnseignantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', MatiereEnseignant::class);
    }

    public function rules(): array
    {
        return [
            'matiere_id' => ['required', 'uuid', 'exists:matieres,id'],
            'enseignant_id' => [
                'required',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if (! app(UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
            'annee_academique_id' => ['required', 'uuid', 'exists:annees_academiques,id'],
            'groupe_id' => ['nullable', 'uuid', 'exists:groupes,id'],
            'type_seance' => ['nullable', 'string', 'max:50'],
            'is_principal' => ['boolean'],
        ];
    }
}
