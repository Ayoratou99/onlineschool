<?php

namespace Modules\Academique\DTOs\ProgrammeDetail;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\ProgrammeDetail\UpdateProgrammeDetailRequest;

final readonly class UpdateProgrammeDetailDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $programmeId,
        public ?string $ueId,
        public ?string $matiereId,
        public ?int $ordre,
        public ?string $observation,
    ) {}

    public static function fromRequest(UpdateProgrammeDetailRequest $request): self
    {
        $v = $request->validated();
        return new self(
            programmeId: $v['programme_id'] ?? null,
            ueId: $v['ue_id'] ?? null,
            matiereId: $v['matiere_id'] ?? null,
            ordre: isset($v['ordre']) ? (int) $v['ordre'] : null,
            observation: $v['observation'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->programmeId !== null) $data['programme_id'] = $this->programmeId;
        if ($this->ueId !== null) $data['ue_id'] = $this->ueId;
        if ($this->matiereId !== null) $data['matiere_id'] = $this->matiereId;
        if ($this->ordre !== null) $data['ordre'] = $this->ordre;
        if ($this->observation !== null) $data['observation'] = $this->observation;
        return $data;
    }
}
