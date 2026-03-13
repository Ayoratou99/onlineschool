<?php

namespace Modules\Academique\DTOs\Parcours;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Parcours\StoreParcoursRequest;

final readonly class CreateParcoursDTO implements ArrayableDTO
{
    public function __construct(
        public string $filiereId,
        public string $code,
        public string $libelle,
        public ?string $description,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreParcoursRequest $request): self
    {
        return new self(
            filiereId: $request->validated('filiere_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            description: $request->validated('description'),
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'filiere_id' => $this->filiereId,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'description' => $this->description,
            'is_active' => $this->isActive,
        ];
    }
}
