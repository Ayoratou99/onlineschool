<?php

namespace Modules\Academique\DTOs\Matiere;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Matiere\StoreMatiereRequest;

final readonly class CreateMatiereDTO implements ArrayableDTO
{
    public function __construct(
        public string $ueId,
        public string $code,
        public string $libelle,
        public ?float $credits,
        public ?float $coefficient,
        public ?int $vhCm,
        public ?int $vhTd,
        public ?int $vhTp,
        public bool $estCompensable,
        public ?float $noteEliminatoire,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreMatiereRequest $request): self
    {
        return new self(
            ueId: $request->validated('ue_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            credits: $request->validated('credits') !== null ? (float) $request->validated('credits') : null,
            coefficient: $request->validated('coefficient') !== null ? (float) $request->validated('coefficient') : null,
            vhCm: $request->validated('vh_cm') !== null ? (int) $request->validated('vh_cm') : null,
            vhTd: $request->validated('vh_td') !== null ? (int) $request->validated('vh_td') : null,
            vhTp: $request->validated('vh_tp') !== null ? (int) $request->validated('vh_tp') : null,
            estCompensable: (bool) ($request->validated('est_compensable') ?? false),
            noteEliminatoire: $request->validated('note_eliminatoire') !== null ? (float) $request->validated('note_eliminatoire') : null,
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'ue_id' => $this->ueId,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'credits' => $this->credits,
            'coefficient' => $this->coefficient,
            'vh_cm' => $this->vhCm,
            'vh_td' => $this->vhTd,
            'vh_tp' => $this->vhTp,
            'est_compensable' => $this->estCompensable,
            'note_eliminatoire' => $this->noteEliminatoire,
            'is_active' => $this->isActive,
        ];
    }
}
