<?php

namespace Modules\Academique\Http\Requests\Parcours;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Parcours;

class StoreParcoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Parcours::class);
    }

    public function rules(): array
    {
        return [
            'filiere_id' => ['required', 'uuid', 'exists:filieres,id'],
            'code' => ['required', 'string', 'max:50', 'unique:parcours,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
