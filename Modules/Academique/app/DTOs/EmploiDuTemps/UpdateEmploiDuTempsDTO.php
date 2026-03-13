<?php

namespace Modules\Academique\DTOs\EmploiDuTemps;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\EmploiDuTemps\UpdateEmploiDuTempsRequest;

final readonly class UpdateEmploiDuTempsDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $semestreId,
        public ?string $niveauId,
        public ?string $groupeId,
        public ?string $matiereId,
        public ?string $salleId,
        public ?string $enseignantId,
        public ?string $anneeAcademiqueId,
        public ?string $typeSeance,
        public ?string $jour,
        public ?string $heureDebut,
        public ?string $heureFin,
        public ?string $frequence,
        public ?string $dateSpecifique,
        public ?string $dateDebutEffectif,
        public ?string $dateFinEffectif,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateEmploiDuTempsRequest $request): self
    {
        $v = $request->validated();
        return new self(
            semestreId: $v['semestre_id'] ?? null,
            niveauId: $v['niveau_id'] ?? null,
            groupeId: $v['groupe_id'] ?? null,
            matiereId: $v['matiere_id'] ?? null,
            salleId: $v['salle_id'] ?? null,
            enseignantId: $v['enseignant_id'] ?? null,
            anneeAcademiqueId: $v['annee_academique_id'] ?? null,
            typeSeance: $v['type_seance'] ?? null,
            jour: $v['jour'] ?? null,
            heureDebut: $v['heure_debut'] ?? null,
            heureFin: $v['heure_fin'] ?? null,
            frequence: $v['frequence'] ?? null,
            dateSpecifique: $v['date_specifique'] ?? null,
            dateDebutEffectif: $v['date_debut_effectif'] ?? null,
            dateFinEffectif: $v['date_fin_effectif'] ?? null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->semestreId !== null) $data['semestre_id'] = $this->semestreId;
        if ($this->niveauId !== null) $data['niveau_id'] = $this->niveauId;
        if ($this->groupeId !== null) $data['groupe_id'] = $this->groupeId;
        if ($this->matiereId !== null) $data['matiere_id'] = $this->matiereId;
        if ($this->salleId !== null) $data['salle_id'] = $this->salleId;
        if ($this->enseignantId !== null) $data['enseignant_id'] = $this->enseignantId;
        if ($this->anneeAcademiqueId !== null) $data['annee_academique_id'] = $this->anneeAcademiqueId;
        if ($this->typeSeance !== null) $data['type_seance'] = $this->typeSeance;
        if ($this->jour !== null) $data['jour'] = $this->jour;
        if ($this->heureDebut !== null) $data['heure_debut'] = $this->heureDebut;
        if ($this->heureFin !== null) $data['heure_fin'] = $this->heureFin;
        if ($this->frequence !== null) $data['frequence'] = $this->frequence;
        if ($this->dateSpecifique !== null) $data['date_specifique'] = $this->dateSpecifique;
        if ($this->dateDebutEffectif !== null) $data['date_debut_effectif'] = $this->dateDebutEffectif;
        if ($this->dateFinEffectif !== null) $data['date_fin_effectif'] = $this->dateFinEffectif;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
