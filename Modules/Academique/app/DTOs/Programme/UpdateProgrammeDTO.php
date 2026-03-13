<?php

namespace Modules\Academique\DTOs\Programme;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Programme\UpdateProgrammeRequest;

final readonly class UpdateProgrammeDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $niveauId,
        public ?string $anneeAcademiqueId,
        public ?int $version,
        public ?bool $isActive,
        public ?string $validePar,
        public ?string $valideLe,
    ) {}

    public static function fromRequest(UpdateProgrammeRequest $request): self
    {
        $v = $request->validated();
        return new self(
            niveauId: $v['niveau_id'] ?? null,
            anneeAcademiqueId: $v['annee_academique_id'] ?? null,
            version: isset($v['version']) ? (int) $v['version'] : null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
            validePar: $v['valide_par'] ?? null,
            valideLe: $v['valide_le'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->niveauId !== null) $data['niveau_id'] = $this->niveauId;
        if ($this->anneeAcademiqueId !== null) $data['annee_academique_id'] = $this->anneeAcademiqueId;
        if ($this->version !== null) $data['version'] = $this->version;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        if ($this->validePar !== null) $data['valide_par'] = $this->validePar;
        if ($this->valideLe !== null) $data['valide_le'] = $this->valideLe;
        return $data;
    }
}
