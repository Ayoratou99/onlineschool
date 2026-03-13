<?php

namespace Modules\Academique\DTOs\Groupe;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Groupe\StoreGroupeRequest;

final readonly class CreateGroupeDTO implements ArrayableDTO
{
    public function __construct(
        public string $niveauId,
        public string $anneeAcademiqueId,
        public string $code,
        public string $libelle,
        public ?string $type,
        public ?int $effectifMax,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreGroupeRequest $request): self
    {
        return new self(
            niveauId: $request->validated('niveau_id'),
            anneeAcademiqueId: $request->validated('annee_academique_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            type: $request->validated('type'),
            effectifMax: $request->validated('effectif_max') !== null ? (int) $request->validated('effectif_max') : null,
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'niveau_id' => $this->niveauId,
            'annee_academique_id' => $this->anneeAcademiqueId,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'type' => $this->type,
            'effectif_max' => $this->effectifMax,
            'is_active' => $this->isActive,
        ];
    }
}
