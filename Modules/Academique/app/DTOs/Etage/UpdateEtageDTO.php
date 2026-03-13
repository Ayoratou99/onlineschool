<?php

namespace Modules\Academique\DTOs\Etage;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Etage\UpdateEtageRequest;

final readonly class UpdateEtageDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $batimentId,
        public ?int $numero,
        public ?string $libelle,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateEtageRequest $request): self
    {
        $v = $request->validated();
        return new self(
            batimentId: $v['batiment_id'] ?? null,
            numero: isset($v['numero']) ? (int) $v['numero'] : null,
            libelle: $v['libelle'] ?? null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->batimentId !== null) $data['batiment_id'] = $this->batimentId;
        if ($this->numero !== null) $data['numero'] = $this->numero;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
