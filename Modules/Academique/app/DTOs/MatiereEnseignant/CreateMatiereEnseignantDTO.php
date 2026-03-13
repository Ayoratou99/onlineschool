<?php

namespace Modules\Academique\DTOs\MatiereEnseignant;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\MatiereEnseignant\StoreMatiereEnseignantRequest;

final readonly class CreateMatiereEnseignantDTO implements ArrayableDTO
{
    public function __construct(
        public string $matiereId,
        public string $enseignantId,
        public string $anneeAcademiqueId,
        public ?string $groupeId,
        public ?string $typeSeance,
        public bool $isPrincipal,
    ) {}

    public static function fromRequest(StoreMatiereEnseignantRequest $request): self
    {
        return new self(
            matiereId: $request->validated('matiere_id'),
            enseignantId: $request->validated('enseignant_id'),
            anneeAcademiqueId: $request->validated('annee_academique_id'),
            groupeId: $request->validated('groupe_id'),
            typeSeance: $request->validated('type_seance'),
            isPrincipal: (bool) ($request->validated('is_principal') ?? false),
        );
    }

    public function toArray(): array
    {
        return [
            'matiere_id' => $this->matiereId,
            'enseignant_id' => $this->enseignantId,
            'annee_academique_id' => $this->anneeAcademiqueId,
            'groupe_id' => $this->groupeId,
            'type_seance' => $this->typeSeance,
            'is_principal' => $this->isPrincipal,
        ];
    }
}
