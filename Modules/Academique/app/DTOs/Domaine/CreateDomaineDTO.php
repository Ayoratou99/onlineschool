<?php

namespace Modules\Academique\DTOs\Domaine;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Domaine\StoreDomaineRequest;

final readonly class CreateDomaineDTO implements ArrayableDTO
{
    public function __construct(
        public string $code,
        public string $libelle,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreDomaineRequest $request): self
    {
        return new self(
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return ['code' => $this->code, 'libelle' => $this->libelle, 'is_active' => $this->isActive];
    }
}
