<?php

namespace Modules\Academique\DTOs\Batiment;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Batiment\StoreBatimentRequest;

final readonly class CreateBatimentDTO implements ArrayableDTO
{
    public function __construct(
        public string $etablissementId,
        public string $code,
        public string $libelle,
        public ?string $adresse,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreBatimentRequest $request): self
    {
        return new self(
            etablissementId: $request->validated('etablissement_id'),
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            adresse: $request->validated('adresse'),
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'etablissement_id' => $this->etablissementId,
            'code' => $this->code,
            'libelle' => $this->libelle,
            'adresse' => $this->adresse,
            'is_active' => $this->isActive,
        ];
    }
}
