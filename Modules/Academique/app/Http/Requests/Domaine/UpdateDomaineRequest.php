<?php

namespace Modules\Academique\Http\Requests\Domaine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academique\Models\Domaine;

class UpdateDomaineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('domaine'));
    }

    public function rules(): array
    {
        $id = $this->route('domaine')?->id;
        return [
            'code' => ['sometimes', 'string', 'max:50', Rule::unique('domaines', 'code')->ignore($id)],
            'libelle' => ['sometimes', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
