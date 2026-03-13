<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared query validation for index (paginate), getAll and show (populate).
 * All fields optional so GET with no query string is valid.
 */
class IndexQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'     => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'populate' => ['sometimes', 'nullable', 'string'],
            'sort'     => [
                'sometimes',
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== '' && $value !== null && ! json_validate($value)) {
                        $fail('Le format du paramètre sort est invalide.');
                    }
                },
            ],
            'search'   => [
                'sometimes',
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== '' && $value !== null && ! json_validate($value)) {
                        $fail('Le format du paramètre search est invalide.');
                    }
                },
            ],
        ];
    }
}
