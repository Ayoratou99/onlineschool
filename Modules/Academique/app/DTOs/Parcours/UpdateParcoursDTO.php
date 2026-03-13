<?php

namespace Modules\Academique\DTOs\Parcours;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Parcours\UpdateParcoursRequest;

final readonly class UpdateParcoursDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $filiereId,
        public ?string $code,
        public ?string $libelle,
        public ?string $description,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateParcoursRequest $request): self
    {
        $v = $request->validated();
        return new self(
            filiereId: $v['filiere_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            description: $v['description'] ?? null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->filiereId !== null) $data['filiere_id'] = $this->filiereId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->description !== null) $data['description'] = $this->description;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
