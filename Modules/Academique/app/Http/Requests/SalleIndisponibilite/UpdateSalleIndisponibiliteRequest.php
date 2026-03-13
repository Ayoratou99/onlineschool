<?php

namespace Modules\Academique\Http\Requests\SalleIndisponibilite;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\SalleIndisponibilite;

class UpdateSalleIndisponibiliteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('salle_indisponibilite'));
    }

    public function rules(): array
    {
        return [
            'salle_id' => ['sometimes', 'uuid', 'exists:salles,id'],
            'date_debut' => ['sometimes', 'date'],
            'date_fin' => ['sometimes', 'date', 'after_or_equal:date_debut'],
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
