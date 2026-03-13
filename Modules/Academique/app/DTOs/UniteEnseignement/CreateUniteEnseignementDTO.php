<?php

namespace Modules\Academique\DTOs\UniteEnseignement;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\UniteEnseignement\StoreUniteEnseignementRequest;

final readonly class CreateUniteEnseignementDTO implements ArrayableDTO
{
    public function __construct(
        public string $semestreId,
        public string $code,
        public string $libelle,
        public ?string $type,
        public ?float $credits,
        public ?float $coefficient,
        public bool $estCapitalisable,
        public bool $estCompensable,
        public ?float $noteMinimale,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreUniteEnseignementRequest $request): self
    {
        return new self(
            semestreId: $request->validated('semestre_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            type: $request->validated('type'),
            credits: $request->validated('credits') !== null ? (float) $request->validated('credits') : null,
            coefficient: $request->validated('coefficient') !== null ? (float) $request->validated('coefficient') : null,
            estCapitalisable: (bool) ($request->validated('est_capitalisable') ?? false),
            estCompensable: (bool) ($request->validated('est_compensable') ?? false),
            noteMinimale: $request->validated('note_minimale') !== null ? (float) $request->validated('note_minimale') : null,
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'semestre_id' => $this->semestreId,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'type' => $this->type,
            'credits' => $this->credits,
            'coefficient' => $this->coefficient,
            'est_capitalisable' => $this->estCapitalisable,
            'est_compensable' => $this->estCompensable,
            'note_minimale' => $this->noteMinimale,
            'is_active' => $this->isActive,
        ];
    }
}
