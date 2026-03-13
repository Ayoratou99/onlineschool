<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Requests\Portail;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortailHeroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image_url' => ['nullable', 'string'],
            'badge_texte' => ['nullable', 'string', 'max:100'],
            'titre' => ['sometimes', 'string', 'max:300'],
            'sous_titre' => ['nullable', 'string'],
            'bouton_principal' => ['nullable', 'string', 'max:100'],
            'bouton_secondaire' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
