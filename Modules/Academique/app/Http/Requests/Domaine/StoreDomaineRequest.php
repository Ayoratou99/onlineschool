<?php

namespace Modules\Academique\Http\Requests\Domaine;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\Domaine;

class StoreDomaineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Domaine::class);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:domaines,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}
