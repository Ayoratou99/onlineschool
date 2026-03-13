<?php

namespace Modules\Academique\DTOs\MatiereEnseignant;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\MatiereEnseignant\UpdateMatiereEnseignantRequest;

final readonly class UpdateMatiereEnseignantDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $matiereId,
        public ?string $enseignantId,
        public ?string $anneeAcademiqueId,
        public ?string $groupeId,
        public ?string $typeSeance,
        public ?bool $isPrincipal,
    ) {}

    public static function fromRequest(UpdateMatiereEnseignantRequest $request): self
    {
        $v = $request->validated();
        return new self(
            matiereId: $v['matiere_id'] ?? null,
            enseignantId: $v['enseignant_id'] ?? null,
            anneeAcademiqueId: $v['annee_academique_id'] ?? null,
            groupeId: $v['groupe_id'] ?? null,
            typeSeance: $v['type_seance'] ?? null,
            isPrincipal: isset($v['is_principal']) ? (bool) $v['is_principal'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->matiereId !== null) $data['matiere_id'] = $this->matiereId;
        if ($this->enseignantId !== null) $data['enseignant_id'] = $this->enseignantId;
        if ($this->anneeAcademiqueId !== null) $data['annee_academique_id'] = $this->anneeAcademiqueId;
        if ($this->groupeId !== null) $data['groupe_id'] = $this->groupeId;
        if ($this->typeSeance !== null) $data['type_seance'] = $this->typeSeance;
        if ($this->isPrincipal !== null) $data['is_principal'] = $this->isPrincipal;
        return $data;
    }
}
