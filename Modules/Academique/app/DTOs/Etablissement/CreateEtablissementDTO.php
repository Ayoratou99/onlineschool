<?php

namespace Modules\Academique\DTOs\Etablissement;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Etablissement\StoreEtablissementRequest;

final readonly class CreateEtablissementDTO implements ArrayableDTO
{
    public function __construct(
        public string $code,
        public string $libelle,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreEtablissementRequest $request): self
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
