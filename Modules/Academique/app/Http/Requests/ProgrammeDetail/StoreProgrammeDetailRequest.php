<?php

namespace Modules\Academique\Http\Requests\ProgrammeDetail;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\ProgrammeDetail;

class StoreProgrammeDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', ProgrammeDetail::class);
    }

    public function rules(): array
    {
        return [
            'programme_id' => ['required', 'uuid', 'exists:programmes,id'],
            'ue_id' => ['required', 'uuid', 'exists:unites_enseignement,id'],
            'matiere_id' => ['required', 'uuid', 'exists:matieres,id'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'observation' => ['nullable', 'string'],
        ];
    }
}
