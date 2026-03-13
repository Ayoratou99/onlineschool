<?php

namespace Modules\Academique\DTOs\Semestre;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Semestre\UpdateSemestreRequest;

final readonly class UpdateSemestreDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $niveauId,
        public ?string $anneeAcademiqueId,
        public ?string $code,
        public ?string $libelle,
        public ?string $type,
        public ?int $ordre,
        public ?string $dateDebut,
        public ?string $dateFin,
        public ?string $dateDebutExamen,
        public ?string $dateFinExamen,
        public ?bool $isLocked,
    ) {}

    public static function fromRequest(UpdateSemestreRequest $request): self
    {
        $v = $request->validated();
        return new self(
            niveauId: $v['niveau_id'] ?? null,
            anneeAcademiqueId: $v['annee_academique_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            type: $v['type'] ?? null,
            ordre: isset($v['ordre']) ? (int) $v['ordre'] : null,
            dateDebut: $v['date_debut'] ?? null,
            dateFin: $v['date_fin'] ?? null,
            dateDebutExamen: $v['date_debut_examen'] ?? null,
            dateFinExamen: $v['date_fin_examen'] ?? null,
            isLocked: isset($v['is_locked']) ? (bool) $v['is_locked'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->niveauId !== null) $data['niveau_id'] = $this->niveauId;
        if ($this->anneeAcademiqueId !== null) $data['annee_academique_id'] = $this->anneeAcademiqueId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->type !== null) $data['type'] = $this->type;
        if ($this->ordre !== null) $data['ordre'] = $this->ordre;
        if ($this->dateDebut !== null) $data['date_debut'] = $this->dateDebut;
        if ($this->dateFin !== null) $data['date_fin'] = $this->dateFin;
        if ($this->dateDebutExamen !== null) $data['date_debut_examen'] = $this->dateDebutExamen;
        if ($this->dateFinExamen !== null) $data['date_fin_examen'] = $this->dateFinExamen;
        if ($this->isLocked !== null) $data['is_locked'] = $this->isLocked;
        return $data;
    }
}
