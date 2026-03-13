<?php

namespace Modules\Academique\DTOs\Cycle;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Cycle\StoreCycleRequest;

final readonly class CreateCycleDTO implements ArrayableDTO
{
    public function __construct(
        public string $code,
        public string $libelle,
        public ?string $niveauBacRequis,
        public int $dureeAnnees,
        public ?int $creditsTotal,
        public bool $isActive,
    ) {}

    public static function fromRequest(StoreCycleRequest $request): self
    {
        return new self(
            code: $request->validated('code'),
            libelle: $request->validated('libelle'),
            niveauBacRequis: $request->validated('niveau_bac_requis'),
            dureeAnnees: (int) ($request->validated('duree_annees') ?? 1),
            creditsTotal: $request->validated('credits_total') ? (int) $request->validated('credits_total') : null,
            isActive: (bool) ($request->validated('is_active') ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'libelle' => $this->libelle,
            'niveau_bac_requis' => $this->niveauBacRequis,
            'duree_annees' => $this->dureeAnnees,
            'credits_total' => $this->creditsTotal,
            'is_active' => $this->isActive,
        ];
    }
}
