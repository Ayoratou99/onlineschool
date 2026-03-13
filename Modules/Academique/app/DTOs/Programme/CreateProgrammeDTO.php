<?php

namespace Modules\Academique\DTOs\Programme;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Programme\StoreProgrammeRequest;

final readonly class CreateProgrammeDTO implements ArrayableDTO
{
    public function __construct(
        public string $niveauId,
        public string $anneeAcademiqueId,
        public ?int $version,
        public bool $isActive,
        public ?string $validePar,
        public ?string $valideLe,
    ) {}

    public static function fromRequest(StoreProgrammeRequest $request): self
    {
        return new self(
            niveauId: $request->validated('niveau_id'),
            anneeAcademiqueId: $request->validated('annee_academique_id'),
            version: $request->validated('version') !== null ? (int) $request->validated('version') : null,
            isActive: (bool) ($request->validated('is_active') ?? true),
            validePar: $request->validated('valide_par'),
            valideLe: $request->validated('valide_le'),
        );
    }

    public function toArray(): array
    {
        return [
            'niveau_id' => $this->niveauId,
            'annee_academique_id' => $this->anneeAcademiqueId,
            'version' => $this->version,
            'is_active' => $this->isActive,
            'valide_par' => $this->validePar,
            'valide_le' => $this->valideLe,
        ];
    }
}
