<?php

namespace Modules\Academique\DTOs\Groupe;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Groupe\UpdateGroupeRequest;

final readonly class UpdateGroupeDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $niveauId,
        public ?string $anneeAcademiqueId,
        public ?string $code,
        public ?string $libelle,
        public ?string $type,
        public ?int $effectifMax,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateGroupeRequest $request): self
    {
        $v = $request->validated();
        return new self(
            niveauId: $v['niveau_id'] ?? null,
            anneeAcademiqueId: $v['annee_academique_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            type: $v['type'] ?? null,
            effectifMax: isset($v['effectif_max']) ? (int) $v['effectif_max'] : null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->niveauId !== null) $data['niveau_id'] = $this->niveauId;
        if ($this->anneeAcademiqueId !== null) $data['annee_academique_id'] = $this->anneeAcademiqueId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->type !== null) $data['type'] = $this->type;
        if ($this->effectifMax !== null) $data['effectif_max'] = $this->effectifMax;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
