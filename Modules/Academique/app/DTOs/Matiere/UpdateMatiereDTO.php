<?php

namespace Modules\Academique\DTOs\Matiere;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Matiere\UpdateMatiereRequest;

final readonly class UpdateMatiereDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $ueId,
        public ?string $code,
        public ?string $libelle,
        public ?float $credits,
        public ?float $coefficient,
        public ?int $vhCm,
        public ?int $vhTd,
        public ?int $vhTp,
        public ?bool $estCompensable,
        public ?float $noteEliminatoire,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateMatiereRequest $request): self
    {
        $v = $request->validated();
        return new self(
            ueId: $v['ue_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            credits: isset($v['credits']) ? (float) $v['credits'] : null,
            coefficient: isset($v['coefficient']) ? (float) $v['coefficient'] : null,
            vhCm: isset($v['vh_cm']) ? (int) $v['vh_cm'] : null,
            vhTd: isset($v['vh_td']) ? (int) $v['vh_td'] : null,
            vhTp: isset($v['vh_tp']) ? (int) $v['vh_tp'] : null,
            estCompensable: isset($v['est_compensable']) ? (bool) $v['est_compensable'] : null,
            noteEliminatoire: isset($v['note_eliminatoire']) ? (float) $v['note_eliminatoire'] : null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->ueId !== null) $data['ue_id'] = $this->ueId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->credits !== null) $data['credits'] = $this->credits;
        if ($this->coefficient !== null) $data['coefficient'] = $this->coefficient;
        if ($this->vhCm !== null) $data['vh_cm'] = $this->vhCm;
        if ($this->vhTd !== null) $data['vh_td'] = $this->vhTd;
        if ($this->vhTp !== null) $data['vh_tp'] = $this->vhTp;
        if ($this->estCompensable !== null) $data['est_compensable'] = $this->estCompensable;
        if ($this->noteEliminatoire !== null) $data['note_eliminatoire'] = $this->noteEliminatoire;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
