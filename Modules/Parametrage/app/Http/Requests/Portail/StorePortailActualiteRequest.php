<?php

declare(strict_types=1);

namespace Modules\Parametrage\Http\Requests\Portail;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Parametrage\Models\PortailActualite;

class StorePortailActualiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:300'],
            'contenu' => ['required', 'string'],
            'image_url' => ['nullable', 'string'],
            'categorie' => ['required', 'string', 'in:info,urgent,evenement,resultat'],
            'ciblage' => ['sometimes', 'string', 'in:tous,etudiants,staff'],
            'is_epingle' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'publie_le' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
