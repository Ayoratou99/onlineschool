<?php

namespace Modules\Parametrage\DTOs\AnneeAcademique;

use App\Contracts\ArrayableDTO;
use Modules\Parametrage\Http\Requests\AnneeAcademique\StoreAnneeAcademiqueRequest;

final readonly class CreateAnneeAcademiqueDTO implements ArrayableDTO
{
    public function __construct(
        public string $code,
        public string $libelle,
        public string $dateDebut,
        public string $dateFin,
        public bool $isActive,
        public string $createdBy,
    ) {}

    public static function fromRequest(StoreAnneeAcademiqueRequest $request): self
    {
        return new self(
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            dateDebut: $request->validated('date_debut'),
            dateFin: $request->validated('date_fin'),
            isActive: $request->validated('is_active', true),
            createdBy: $request->validated('created_by'),
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'libelle' => $this->libelle,
            'date_debut' => $this->dateDebut,
            'date_fin' => $this->dateFin,
            'is_active' => $this->isActive,
            'created_by' => $this->createdBy,
        ];
    }
}
