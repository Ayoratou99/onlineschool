<?php

namespace Modules\Academique\Http\Requests\EmploiDuTempsException;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\EmploiDuTempsException;

class StoreEmploiDuTempsExceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', EmploiDuTempsException::class);
    }

    public function rules(): array
    {
        return [
            'emploi_du_temps_id' => ['required', 'uuid', 'exists:emplois_du_temps,id'],
            'date_concernee' => ['required', 'date'],
            'type' => ['nullable', 'string', 'max:50'],
            'nouvelle_salle_id' => ['nullable', 'uuid', 'exists:salles,id'],
            'nouvel_enseignant_id' => [
                'nullable',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if (! app(UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
            'nouvelle_heure_debut' => ['nullable', 'date_format:H:i'],
            'nouvelle_heure_fin' => ['nullable', 'date_format:H:i', 'after:nouvelle_heure_debut'],
            'motif' => ['nullable', 'string'],
            'created_by' => [
                'required',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if (! app(UserExistsCheckerInterface::class)->exists($value)) {
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
