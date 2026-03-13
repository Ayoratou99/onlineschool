<?php

namespace Modules\Academique\DTOs\EmploiDuTempsException;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\EmploiDuTempsException\StoreEmploiDuTempsExceptionRequest;

final readonly class CreateEmploiDuTempsExceptionDTO implements ArrayableDTO
{
    public function __construct(
        public string $emploiDuTempsId,
        public string $dateConcernee,
        public ?string $type,
        public ?string $nouvelleSalleId,
        public ?string $nouvelEnseignantId,
        public ?string $nouvelleHeureDebut,
        public ?string $nouvelleHeureFin,
        public ?string $motif,
        public string $createdBy,
    ) {}

    public static function fromRequest(StoreEmploiDuTempsExceptionRequest $request): self
    {
        return new self(
            emploiDuTempsId: $request->validated('emploi_du_temps_id'),
            dateConcernee: $request->validated('date_concernee'),
            type: $request->validated('type'),
            nouvelleSalleId: $request->validated('nouvelle_salle_id'),
            nouvelEnseignantId: $request->validated('nouvel_enseignant_id'),
            nouvelleHeureDebut: $request->validated('nouvelle_heure_debut'),
            nouvelleHeureFin: $request->validated('nouvelle_heure_fin'),
            motif: $request->validated('motif'),
            createdBy: $request->validated('created_by'),
        );
    }

    public function toArray(): array
    {
        return [
            'emploi_du_temps_id' => $this->emploiDuTempsId,
            'date_concernee' => $this->dateConcernee,
            'type' => $this->type,
            'nouvelle_salle_id' => $this->nouvelleSalleId,
            'nouvel_enseignant_id' => $this->nouvelEnseignantId,
            'nouvelle_heure_debut' => $this->nouvelleHeureDebut,
            'nouvelle_heure_fin' => $this->nouvelleHeureFin,
            'motif' => $this->motif,
            'created_by' => $this->createdBy,
        ];
    }
}
