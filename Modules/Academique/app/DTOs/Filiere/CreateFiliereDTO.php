<?php

namespace Modules\Academique\DTOs\Filiere;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Filiere\StoreFiliereRequest;

final readonly class CreateFiliereDTO implements ArrayableDTO
{
    public function __construct(
        public string $cycleId,
        public string $domaineId,
        public ?string $responsableId,
        public string $code,
        public string $libelle,
        public ?string $description,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreFiliereRequest $request): self
    {
        return new self(
            cycleId: $request->validated('cycle_id'),
            domaineId: $request->validated('domaine_id'),
            responsableId: $request->validated('responsable_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            description: $request->validated('description'),
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'cycle_id' => $this->cycleId,
            'domaine_id' => $this->domaineId,
            'responsable_id' => $this->responsableId,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'description' => $this->description,
            'is_active' => $this->isActive,
        ];
    }
}
