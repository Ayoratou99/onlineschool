<?php

namespace Modules\Parametrage\DTOs\PortailActualite;

use App\Contracts\ArrayableDTO;
use Modules\Parametrage\Http\Requests\Portail\UpdatePortailActualiteRequest;

final readonly class UpdatePortailActualiteDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $titre,
        public ?string $contenu,
        public ?string $image_url,
        public ?string $categorie,
        public ?string $ciblage,
        public ?bool $is_epingle,
        public ?bool $is_active,
        public ?string $publie_le,
    ) {}

    public static function fromRequest(UpdatePortailActualiteRequest $request): self
    {
        return new self(
            titre: $request->validated('titre') ?? null,
            contenu: $request->validated('contenu') ?? null,
            image_url: $request->validated('image_url') ?? null,
            categorie: $request->validated('categorie') ?? null,
            ciblage: $request->validated('ciblage') ?? null,
            is_epingle: $request->validated('is_epingle') ?? null,
            is_active: $request->validated('is_active') ?? null,
            publie_le: $request->validated('publie_le') ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->titre !== null) $data['titre'] = $this->titre;
        if ($this->contenu !== null) $data['contenu'] = $this->contenu;
        if ($this->image_url !== null) $data['image_url'] = $this->image_url;
        if ($this->categorie !== null) $data['categorie'] = $this->categorie;
        if ($this->ciblage !== null) $data['ciblage'] = $this->ciblage;
        if ($this->is_epingle !== null) $data['is_epingle'] = $this->is_epingle;
        if ($this->is_active !== null) $data['is_active'] = $this->is_active;
        if ($this->publie_le !== null) $data['publie_le'] = $this->publie_le;
        return $data;
    }

}