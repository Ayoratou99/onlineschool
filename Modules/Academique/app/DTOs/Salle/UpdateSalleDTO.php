<?php

namespace Modules\Academique\DTOs\Salle;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Salle\UpdateSalleRequest;

final readonly class UpdateSalleDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $batimentId,
        public ?string $etageId,
        public ?string $code,
        public ?string $libelle,
        public ?string $type,
        public ?int $capacite,
        public ?bool $hasProjecteur,
        public ?bool $hasClimatisation,
        public ?bool $hasTableauBlanc,
        public ?bool $hasInternet,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateSalleRequest $request): self
    {
        $v = $request->validated();
        return new self(
            batimentId: $v['batiment_id'] ?? null,
            etageId: $v['etage_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            type: $v['type'] ?? null,
            capacite: isset($v['capacite']) ? (int) $v['capacite'] : null,
            hasProjecteur: isset($v['has_projecteur']) ? (bool) $v['has_projecteur'] : null,
            hasClimatisation: isset($v['has_climatisation']) ? (bool) $v['has_climatisation'] : null,
            hasTableauBlanc: isset($v['has_tableau_blanc']) ? (bool) $v['has_tableau_blanc'] : null,
            hasInternet: isset($v['has_internet']) ? (bool) $v['has_internet'] : null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->batimentId !== null) $data['batiment_id'] = $this->batimentId;
        if ($this->etageId !== null) $data['etage_id'] = $this->etageId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->type !== null) $data['type'] = $this->type;
        if ($this->capacite !== null) $data['capacite'] = $this->capacite;
        if ($this->hasProjecteur !== null) $data['has_projecteur'] = $this->hasProjecteur;
        if ($this->hasClimatisation !== null) $data['has_climatisation'] = $this->hasClimatisation;
        if ($this->hasTableauBlanc !== null) $data['has_tableau_blanc'] = $this->hasTableauBlanc;
        if ($this->hasInternet !== null) $data['has_internet'] = $this->hasInternet;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
