<?php

namespace Modules\Academique\DTOs\SalleIndisponibilite;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\SalleIndisponibilite\StoreSalleIndisponibiliteRequest;

final readonly class CreateSalleIndisponibiliteDTO implements ArrayableDTO
{
    public function __construct(
        public string $salleId,
        public string $dateDebut,
        public string $dateFin,
        public ?string $motif,
        public string $createdBy,
    ) {}

    public static function fromRequest(StoreSalleIndisponibiliteRequest $request): self
    {
        return new self(
            salleId: $request->validated('salle_id'),
            dateDebut: $request->validated('date_debut'),
            dateFin: $request->validated('date_fin'),
            motif: $request->validated('motif'),
            createdBy: $request->validated('created_by'),
        );
    }

    public function toArray(): array
    {
        return [
            'salle_id' => $this->salleId,
            'date_debut' => $this->dateDebut,
            'date_fin' => $this->dateFin,
            'motif' => $this->motif,
            'created_by' => $this->createdBy,
        ];
    }
}
