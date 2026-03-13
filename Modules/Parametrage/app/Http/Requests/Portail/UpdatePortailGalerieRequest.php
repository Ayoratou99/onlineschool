<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Requests\Portail;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortailGalerieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => ['sometimes', 'nullable', 'string'],
            'legende' => ['nullable', 'string', 'max:200'],
            'alt_text' => ['nullable', 'string', 'max:200'],
            'ordre' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
