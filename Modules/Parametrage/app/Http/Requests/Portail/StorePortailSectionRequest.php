<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Requests\Portail;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Parametrage\Models\PortailSection;

class StorePortailSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:' . implode(',', PortailSection::TYPES)],
            'titre' => ['nullable', 'string', 'max:200'],
            'contenu' => ['nullable', 'array'],
            'ordre' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'bg_couleur' => ['nullable', 'string', 'max:30'],
        ];
    }
}
