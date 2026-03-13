<?php

namespace Modules\Academique\DTOs\Salle;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Salle\StoreSalleRequest;

final readonly class CreateSalleDTO implements ArrayableDTO
{
    public function __construct(
        public string $batimentId,
        public string $etageId,
        public string $code,
        public string $libelle,
        public ?string $type,
        public ?int $capacite,
        public bool $hasProjecteur,
        public bool $hasClimatisation,
        public bool $hasTableauBlanc,
        public bool $hasInternet,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreSalleRequest $request): self
    {
        return new self(
            batimentId: $request->validated('batiment_id'),
            etageId: $request->validated('etage_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            type: $request->validated('type'),
            capacite: $request->validated('capacite') !== null ? (int) $request->validated('capacite') : null,
            hasProjecteur: (bool) ($request->validated('has_projecteur') ?? false),
            hasClimatisation: (bool) ($request->validated('has_climatisation') ?? false),
            hasTableauBlanc: (bool) ($request->validated('has_tableau_blanc') ?? false),
            hasInternet: (bool) ($request->validated('has_internet') ?? false),
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'batiment_id' => $this->batimentId,
            'etage_id' => $this->etageId,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'type' => $this->type,
            'capacite' => $this->capacite,
            'has_projecteur' => $this->hasProjecteur,
            'has_climatisation' => $this->hasClimatisation,
            'has_tableau_blanc' => $this->hasTableauBlanc,
            'has_internet' => $this->hasInternet,
            'is_active' => $this->isActive,
        ];
    }
}
