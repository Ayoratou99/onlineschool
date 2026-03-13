<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Requests\Portail;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortailMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => ['sometimes', 'string', 'max:80'],
            'url' => ['sometimes', 'string', 'max:500'],
            'ordre' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
