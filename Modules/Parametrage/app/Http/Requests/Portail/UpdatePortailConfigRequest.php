<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Requests\Portail;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortailConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom_etablissement' => ['sometimes', 'string', 'max:200'],
            'slogan' => ['nullable', 'string', 'max:300'],
            'logo_url' => ['nullable', 'string'],
            'favicon_url' => ['nullable', 'string'],
            'couleur_primaire' => ['sometimes', 'string', 'size:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'couleur_secondaire' => ['sometimes', 'string', 'size:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'couleur_texte' => ['sometimes', 'string', 'size:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:512'],
        ];
    }
}
