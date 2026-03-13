<?php

namespace Modules\Academique\DTOs\Etage;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Etage\StoreEtageRequest;

final readonly class CreateEtageDTO implements ArrayableDTO
{
    public function __construct(
        public string $batimentId,
        public int $numero,
        public string $libelle,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreEtageRequest $request): self
    {
        return new self(
            batimentId: $request->validated('batiment_id'),
            numero: (int) $request->validated('numero'),
            libelle: $request->validated('libelle'),
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'batiment_id' => $this->batimentId,
            'numero' => $this->numero,
            'libelle' => $this->libelle,
            'is_active' => $this->isActive,
        ];
    }
}
