<?php

namespace Modules\Academique\Http\Requests\SalleIndisponibilite;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\SalleIndisponibilite;

class StoreSalleIndisponibiliteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', SalleIndisponibilite::class);
    }

    public function rules(): array
    {
        return [
            'salle_id' => ['required', 'uuid', 'exists:salles,id'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
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
