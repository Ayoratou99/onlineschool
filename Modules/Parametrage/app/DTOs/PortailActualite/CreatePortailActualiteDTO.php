<?php

namespace Modules\Parametrage\DTOs\PortailActualite;

use App\Contracts\ArrayableDTO;
use Modules\Parametrage\Http\Requests\Portail\StorePortailActualiteRequest;
use Modules\Parametrage\Models\PortailActualite;

final readonly class CreatePortailActualiteDTO implements ArrayableDTO
{
    public function __construct(
        public string $titre,
        public string $contenu,
        public string $image_url,
        public string $auteur_id,
        public string $categorie,
        public string $ciblage,
        public bool $is_epingle,
        public bool $is_active,
        public string $publie_le,
    ) {}

    public static function fromRequest(StorePortailActualiteRequest $request): self
    {
        return new self(
            titre: $request->validated('titre'),
            contenu: $request->validated('contenu'),
            image_url: $request->validated('image_url'),
            categorie: $request->validated('categorie'),
            ciblage: $request->validated('ciblage') ?: PortailActualite::CIBLAGE_TOUS,
            is_epingle: $request->validated('is_epingle') ?: false,
            is_active: $request->validated('is_active') ?: true,
            publie_le: $request->validated('publie_le'),
            auteur_id: $request->user()->id,
        );
    }

    public function toArray(): array
    {
        return [
            'titre' => $this->titre,
            'contenu' => $this->contenu,
            'image_url' => $this->image_url,
            'categorie' => $this->categorie,
            'ciblage' => $this->ciblage,
            'is_epingle' => $this->is_epingle,
            'is_active' => $this->is_active,
            'publie_le' => $this->publie_le,
            'auteur_id' => $this->auteur_id,
        ];
    }
}