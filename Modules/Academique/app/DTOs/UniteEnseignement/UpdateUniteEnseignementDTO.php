<?php

namespace Modules\Academique\DTOs\UniteEnseignement;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\UniteEnseignement\UpdateUniteEnseignementRequest;

final readonly class UpdateUniteEnseignementDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $semestreId,
        public ?string $code,
        public ?string $libelle,
        public ?string $type,
        public ?float $credits,
        public ?float $coefficient,
        public ?bool $estCapitalisable,
        public ?bool $estCompensable,
        public ?float $noteMinimale,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateUniteEnseignementRequest $request): self
    {
        $v = $request->validated();
        return new self(
            semestreId: $v['semestre_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            type: $v['type'] ?? null,
            credits: isset($v['credits']) ? (float) $v['credits'] : null,
            coefficient: isset($v['coefficient']) ? (float) $v['coefficient'] : null,
            estCapitalisable: isset($v['est_capitalisable']) ? (bool) $v['est_capitalisable'] : null,
            estCompensable: isset($v['est_compensable']) ? (bool) $v['est_compensable'] : null,
            noteMinimale: isset($v['note_minimale']) ? (float) $v['note_minimale'] : null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->semestreId !== null) $data['semestre_id'] = $this->semestreId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->type !== null) $data['type'] = $this->type;
        if ($this->credits !== null) $data['credits'] = $this->credits;
        if ($this->coefficient !== null) $data['coefficient'] = $this->coefficient;
        if ($this->estCapitalisable !== null) $data['est_capitalisable'] = $this->estCapitalisable;
        if ($this->estCompensable !== null) $data['est_compensable'] = $this->estCompensable;
        if ($this->noteMinimale !== null) $data['note_minimale'] = $this->noteMinimale;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
