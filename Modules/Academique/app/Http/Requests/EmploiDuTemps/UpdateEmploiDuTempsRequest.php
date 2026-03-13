<?php

namespace Modules\Academique\Http\Requests\EmploiDuTemps;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\EmploiDuTemps;

class UpdateEmploiDuTempsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('emploi_du_temps'));
    }

    public function rules(): array
    {
        return [
            'semestre_id' => ['sometimes', 'uuid', 'exists:semestres,id'],
            'niveau_id' => ['sometimes', 'uuid', 'exists:niveaux,id'],
            'groupe_id' => ['sometimes', 'uuid', 'exists:groupes,id'],
            'matiere_id' => ['sometimes', 'uuid', 'exists:matieres,id'],
            'salle_id' => ['sometimes', 'uuid', 'exists:salles,id'],
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
            'type_seance' => ['nullable', 'string', 'max:50'],
            'jour' => ['nullable', 'string', 'max:20'],
            'heure_debut' => ['nullable', 'date_format:H:i'],
            'heure_fin' => ['nullable', 'date_format:H:i', 'after:heure_debut'],
            'frequence' => ['nullable', 'string', 'max:50'],
            'date_specifique' => ['nullable', 'date'],
            'date_debut_effectif' => ['nullable', 'date'],
            'date_fin_effectif' => ['nullable', 'date', 'after_or_equal:date_debut_effectif'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
