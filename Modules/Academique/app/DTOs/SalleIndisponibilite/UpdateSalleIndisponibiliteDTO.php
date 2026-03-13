<?php

namespace Modules\Academique\DTOs\SalleIndisponibilite;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\SalleIndisponibilite\UpdateSalleIndisponibiliteRequest;

final readonly class UpdateSalleIndisponibiliteDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $salleId,
        public ?string $dateDebut,
        public ?string $dateFin,
        public ?string $motif,
        public ?string $createdBy,
    ) {}

    public static function fromRequest(UpdateSalleIndisponibiliteRequest $request): self
    {
        $v = $request->validated();
        return new self(
            salleId: $v['salle_id'] ?? null,
            dateDebut: $v['date_debut'] ?? null,
            dateFin: $v['date_fin'] ?? null,
            motif: $v['motif'] ?? null,
            createdBy: $v['created_by'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->salleId !== null) $data['salle_id'] = $this->salleId;
        if ($this->dateDebut !== null) $data['date_debut'] = $this->dateDebut;
        if ($this->dateFin !== null) $data['date_fin'] = $this->dateFin;
        if ($this->motif !== null) $data['motif'] = $this->motif;
        if ($this->createdBy !== null) $data['created_by'] = $this->createdBy;
        return $data;
    }
}
