<?php

namespace Modules\Academique\DTOs\ProgrammeDetail;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\ProgrammeDetail\StoreProgrammeDetailRequest;

final readonly class CreateProgrammeDetailDTO implements ArrayableDTO
{
    public function __construct(
        public string $programmeId,
        public string $ueId,
        public string $matiereId,
        public ?int $ordre,
        public ?string $observation,
    ) {}

    public static function fromRequest(StoreProgrammeDetailRequest $request): self
    {
        return new self(
            programmeId: $request->validated('programme_id'),
            ueId: $request->validated('ue_id'),
            matiereId: $request->validated('matiere_id'),
            ordre: $request->validated('ordre') !== null ? (int) $request->validated('ordre') : null,
            observation: $request->validated('observation'),
        );
    }

    public function toArray(): array
    {
        return [
            'programme_id' => $this->programmeId,
            'ue_id' => $this->ueId,
            'matiere_id' => $this->matiereId,
            'ordre' => $this->ordre,
            'observation' => $this->observation,
        ];
    }
}
