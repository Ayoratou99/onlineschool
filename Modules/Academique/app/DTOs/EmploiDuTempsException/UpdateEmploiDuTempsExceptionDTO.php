<?php

namespace Modules\Academique\DTOs\EmploiDuTempsException;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\EmploiDuTempsException\UpdateEmploiDuTempsExceptionRequest;

final readonly class UpdateEmploiDuTempsExceptionDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $emploiDuTempsId,
        public ?string $dateConcernee,
        public ?string $type,
        public ?string $nouvelleSalleId,
        public ?string $nouvelEnseignantId,
        public ?string $nouvelleHeureDebut,
        public ?string $nouvelleHeureFin,
        public ?string $motif,
        public ?string $createdBy,
    ) {}

    public static function fromRequest(UpdateEmploiDuTempsExceptionRequest $request): self
    {
        $v = $request->validated();
        return new self(
            emploiDuTempsId: $v['emploi_du_temps_id'] ?? null,
            dateConcernee: $v['date_concernee'] ?? null,
            type: $v['type'] ?? null,
            nouvelleSalleId: $v['nouvelle_salle_id'] ?? null,
            nouvelEnseignantId: $v['nouvel_enseignant_id'] ?? null,
            nouvelleHeureDebut: $v['nouvelle_heure_debut'] ?? null,
            nouvelleHeureFin: $v['nouvelle_heure_fin'] ?? null,
            motif: $v['motif'] ?? null,
            createdBy: $v['created_by'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->emploiDuTempsId !== null) $data['emploi_du_temps_id'] = $this->emploiDuTempsId;
        if ($this->dateConcernee !== null) $data['date_concernee'] = $this->dateConcernee;
        if ($this->type !== null) $data['type'] = $this->type;
        if ($this->nouvelleSalleId !== null) $data['nouvelle_salle_id'] = $this->nouvelleSalleId;
        if ($this->nouvelEnseignantId !== null) $data['nouvel_enseignant_id'] = $this->nouvelEnseignantId;
        if ($this->nouvelleHeureDebut !== null) $data['nouvelle_heure_debut'] = $this->nouvelleHeureDebut;
        if ($this->nouvelleHeureFin !== null) $data['nouvelle_heure_fin'] = $this->nouvelleHeureFin;
        if ($this->motif !== null) $data['motif'] = $this->motif;
        if ($this->createdBy !== null) $data['created_by'] = $this->createdBy;
        return $data;
    }
}
