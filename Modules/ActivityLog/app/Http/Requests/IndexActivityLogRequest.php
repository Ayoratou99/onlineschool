<?php

namespace Modules\ActivityLog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ActivityLog\Models\ActivityLog;

class IndexActivityLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', ActivityLog::class);
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'populate' => ['sometimes', 'nullable', 'string'],
            'sort' => [
                'sometimes',
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== '' && $value !== null && !json_validate($value)) {
                        $fail('Le format du paramètre sort est invalide.');
                    }
                },
            ],
            'search' => [
                'sometimes',
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== '' && $value !== null && !json_validate($value)) {
                        $fail('Le format du paramètre search est invalide.');
                    }
                },
            ],
            'user_id' => ['sometimes', 'nullable', 'uuid'],
            'entity' => ['sometimes', 'nullable', 'string', 'max:255'],
            'action' => ['sometimes', 'nullable', 'string', 'max:255'],
            'start_date' => ['sometimes', 'nullable', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => 'Le numéro de page doit être un entier.',
            'page.min' => 'Le numéro de page doit être au moins 1.',
            'per_page.integer' => 'Le nombre d\'éléments par page doit être un entier.',
            'per_page.min' => 'Le nombre d\'éléments par page doit être au moins 1.',
            'per_page.max' => 'Le nombre d\'éléments par page ne peut pas dépasser 100.',
            'user_id.uuid' => 'L\'identifiant utilisateur doit être un UUID valide.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'end_date.date' => 'La date de fin doit être une date valide.',
            'end_date.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
        ];
    }

    public function getSort(): ?array
    {
        $value = $this->query('sort');
        return $value && json_validate($value) ? json_decode($value, true) : null;
    }

    public function getSearch(): ?array
    {
        $value = $this->query('search');
        return $value && json_validate($value) ? json_decode($value, true) : null;
    }

    public function getFilters(): array
    {
        return [
            'user_id' => $this->query('user_id'),
            'entity' => $this->query('entity'),
            'action' => $this->query('action'),
            'start_date' => $this->query('start_date'),
            'end_date' => $this->query('end_date'),
        ];
    }
}
