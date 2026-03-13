<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Requests\Portail;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortailContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'adresse' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'horaires_semaine' => ['nullable', 'string', 'max:100'],
            'horaires_samedi' => ['nullable', 'string', 'max:100'],
            'facebook_url' => ['nullable', 'string', 'url'],
            'twitter_url' => ['nullable', 'string', 'url'],
            'linkedin_url' => ['nullable', 'string', 'url'],
            'instagram_url' => ['nullable', 'string', 'url'],
        ];
    }
}
