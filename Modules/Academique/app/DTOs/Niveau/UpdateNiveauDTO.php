<?php

namespace Modules\Academique\DTOs\Niveau;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Niveau\UpdateNiveauRequest;

final readonly class UpdateNiveauDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $filiereId,
        public ?string $parcoursId,
        public ?string $anneeAcademiqueId,
        public ?string $code,
        public ?string $libelle,
        public ?int $ordre,
        public ?int $creditsRequis,
        public ?int $effectifMax,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateNiveauRequest $request): self
    {
        $v = $request->validated();
        return new self(
            filiereId: $v['filiere_id'] ?? null,
            parcoursId: $v['parcours_id'] ?? null,
            anneeAcademiqueId: $v['annee_academique_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            ordre: isset($v['ordre']) ? (int) $v['ordre'] : null,
            creditsRequis: isset($v['credits_requis']) ? (int) $v['credits_requis'] : null,
            effectifMax: isset($v['effectif_max']) ? (int) $v['effectif_max'] : null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->filiereId !== null) $data['filiere_id'] = $this->filiereId;
        if ($this->parcoursId !== null) $data['parcours_id'] = $this->parcoursId;
        if ($this->anneeAcademiqueId !== null) $data['annee_academique_id'] = $this->anneeAcademiqueId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->ordre !== null) $data['ordre'] = $this->ordre;
        if ($this->creditsRequis !== null) $data['credits_requis'] = $this->creditsRequis;
        if ($this->effectifMax !== null) $data['effectif_max'] = $this->effectifMax;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
