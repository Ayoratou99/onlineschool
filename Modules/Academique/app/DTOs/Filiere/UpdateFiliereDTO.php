<?php

namespace Modules\Academique\DTOs\Filiere;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Filiere\UpdateFiliereRequest;

final readonly class UpdateFiliereDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $cycleId,
        public ?string $domaineId,
        public ?string $responsableId,
        public ?string $code,
        public ?string $libelle,
        public ?string $description,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateFiliereRequest $request): self
    {
        $v = $request->validated();
        return new self(
            cycleId: $v['cycle_id'] ?? null,
            domaineId: $v['domaine_id'] ?? null,
            responsableId: $v['responsable_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            description: $v['description'] ?? null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->cycleId !== null) $data['cycle_id'] = $this->cycleId;
        if ($this->domaineId !== null) $data['domaine_id'] = $this->domaineId;
        if ($this->responsableId !== null) $data['responsable_id'] = $this->responsableId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->description !== null) $data['description'] = $this->description;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
