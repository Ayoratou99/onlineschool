<?php

namespace Modules\Academique\Http\Requests\Filiere;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Filiere;

class StoreFiliereRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Filiere::class);
    }

    public function rules(): array
    {
        return [
            'cycle_id' => ['required', 'uuid', 'exists:cycles,id'],
            'domaine_id' => ['required', 'uuid', 'exists:domaines,id'],
            'responsable_id' => [
                'nullable',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if ($value !== null && ! app(UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
            'code' => ['required', 'string', 'max:50', 'unique:filieres,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
