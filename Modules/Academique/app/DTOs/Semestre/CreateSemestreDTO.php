<?php

namespace Modules\Academique\DTOs\Semestre;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Semestre\StoreSemestreRequest;

final readonly class CreateSemestreDTO implements ArrayableDTO
{
    public function __construct(
        public string $niveauId,
        public string $anneeAcademiqueId,
        public string $code,
        public string $libelle,
        public ?string $type,
        public ?int $ordre,
        public ?string $dateDebut,
        public ?string $dateFin,
        public ?string $dateDebutExamen,
        public ?string $dateFinExamen,
        public bool $isLocked,
    ) {}

    public static function fromRequest(StoreSemestreRequest $request): self
    {
        return new self(
            niveauId: $request->validated('niveau_id'),
            anneeAcademiqueId: $request->validated('annee_academique_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            type: $request->validated('type'),
            ordre: $request->validated('ordre') !== null ? (int) $request->validated('ordre') : null,
            dateDebut: $request->validated('date_debut'),
            dateFin: $request->validated('date_fin'),
            dateDebutExamen: $request->validated('date_debut_examen'),
            dateFinExamen: $request->validated('date_fin_examen'),
            isLocked: (bool) ($request->validated('is_locked') ?? false),
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
            'ordre' => $this->ordre,
            'date_debut' => $this->dateDebut,
            'date_fin' => $this->dateFin,
            'date_debut_examen' => $this->dateDebutExamen,
            'date_fin_examen' => $this->dateFinExamen,
            'is_locked' => $this->isLocked,
        ];
    }
}
