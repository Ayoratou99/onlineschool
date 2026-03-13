<?php

namespace Modules\Academique\Http\Requests\Filiere;

use App\Contracts\UserExistsCheckerInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Filiere;

class UpdateFiliereRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('filiere'));
    }

    public function rules(): array
    {
        $id = $this->route('filiere')?->id;
        return [
            'cycle_id' => ['sometimes', 'uuid', 'exists:cycles,id'],
            'domaine_id' => ['sometimes', 'uuid', 'exists:domaines,id'],
            'responsable_id' => [
                'nullable',
                'uuid',
                function (string $attr, mixed $value, \Closure $fail) {
                    if ($value !== null && ! app(UserExistsCheckerInterface::class)->exists($value)) {
                        $fail("L'utilisateur spécifié n'existe pas.");
                    }
                },
            ],
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('filieres', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
