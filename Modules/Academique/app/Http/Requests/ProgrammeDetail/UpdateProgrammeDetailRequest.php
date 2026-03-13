<?php

namespace Modules\Academique\Http\Requests\ProgrammeDetail;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Academique\Models\ProgrammeDetail;

class UpdateProgrammeDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('programme_detail'));
    }

    public function rules(): array
    {
        return [
            'programme_id' => ['sometimes', 'uuid', 'exists:programmes,id'],
            'ue_id' => ['sometimes', 'uuid', 'exists:unites_enseignement,id'],
            'matiere_id' => ['sometimes', 'uuid', 'exists:matieres,id'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'observation' => ['nullable', 'string'],
        ];
    }
}
