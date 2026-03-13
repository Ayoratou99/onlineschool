<?php

namespace Modules\Parametrage\DTOs\AnneeAcademique;

use App\Contracts\ArrayableDTO;
use Modules\Parametrage\Http\Requests\AnneeAcademique\UpdateAnneeAcademiqueRequest;

final readonly class UpdateAnneeAcademiqueDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $code,
        public ?string $libelle,
        public ?string $dateDebut,
        public ?string $dateFin,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateAnneeAcademiqueRequest $request): self
    {
        $validated = $request->validated();
        return new self(
            code: $validated['code'] ?? null,
            libelle: $validated['libelle'] ?? null,
            dateDebut: $validated['date_debut'] ?? null,
            dateFin: $validated['date_fin'] ?? null,
            isActive: isset($validated['is_active']) ? (bool) $validated['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->dateDebut !== null) $data['date_debut'] = $this->dateDebut;
        if ($this->dateFin !== null) $data['date_fin'] = $this->dateFin;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
