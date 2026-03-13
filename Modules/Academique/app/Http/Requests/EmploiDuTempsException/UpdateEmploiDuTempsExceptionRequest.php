<?php

namespace Modules\Academique\Http\Requests\EmploiDuTempsException;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\EmploiDuTempsException;

class UpdateEmploiDuTempsExceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('emploi_du_temps_exception'));
    }

    public function rules(): array
    {
        return [
            'emploi_du_temps_id' => ['sometimes', 'uuid', 'exists:emplois_du_temps,id'],
            'date_concernee' => ['sometimes', 'date'],
            'type' => ['nullable', 'string', 'max:50'],
            'nouvelle_salle_id' => ['nullable', 'uuid', 'exists:salles,id'],
            'nouvel_enseignant_id' => [
                'nullable',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if ($value !== null && ! app(UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
            'nouvelle_heure_debut' => ['nullable', 'date_format:H:i'],
            'nouvelle_heure_fin' => ['nullable', 'date_format:H:i', 'after:nouvelle_heure_debut'],
            'motif' => ['nullable', 'string'],
            'created_by' => [
                'sometimes',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if (! app(UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
        ];
    }
}
