<?php

namespace Modules\Academique\DTOs\EmploiDuTemps;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\EmploiDuTemps\StoreEmploiDuTempsRequest;

final readonly class CreateEmploiDuTempsDTO implements ArrayableDTO
{
    public function __construct(
        public string $semestreId,
        public string $niveauId,
        public string $groupeId,
        public string $matiereId,
        public string $salleId,
        public string $enseignantId,
        public string $anneeAcademiqueId,
        public ?string $typeSeance,
        public ?string $jour,
        public ?string $heureDebut,
        public ?string $heureFin,
        public ?string $frequence,
        public ?string $dateSpecifique,
        public ?string $dateDebutEffectif,
        public ?string $dateFinEffectif,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreEmploiDuTempsRequest $request): self
    {
        return new self(
            semestreId: $request->validated('semestre_id'),
            niveauId: $request->validated('niveau_id'),
            groupeId: $request->validated('groupe_id'),
            matiereId: $request->validated('matiere_id'),
            salleId: $request->validated('salle_id'),
            enseignantId: $request->validated('enseignant_id'),
            anneeAcademiqueId: $request->validated('annee_academique_id'),
            typeSeance: $request->validated('type_seance'),
            jour: $request->validated('jour'),
            heureDebut: $request->validated('heure_debut'),
            heureFin: $request->validated('heure_fin'),
            frequence: $request->validated('frequence'),
            dateSpecifique: $request->validated('date_specifique'),
            dateDebutEffectif: $request->validated('date_debut_effectif'),
            dateFinEffectif: $request->validated('date_fin_effectif'),
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'semestre_id' => $this->semestreId,
            'niveau_id' => $this->niveauId,
            'groupe_id' => $this->groupeId,
            'matiere_id' => $this->matiereId,
            'salle_id' => $this->salleId,
            'enseignant_id' => $this->enseignantId,
            'annee_academique_id' => $this->anneeAcademiqueId,
            'type_seance' => $this->typeSeance,
            'jour' => $this->jour,
            'heure_debut' => $this->heureDebut,
            'heure_fin' => $this->heureFin,
            'frequence' => $this->frequence,
            'date_specifique' => $this->dateSpecifique,
            'date_debut_effectif' => $this->dateDebutEffectif,
            'date_fin_effectif' => $this->dateFinEffectif,
            'is_active' => $this->isActive,
        ];
    }
}
