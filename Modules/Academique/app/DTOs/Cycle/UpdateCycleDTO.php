<?php

namespace Modules\Academique\DTOs\Cycle;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Cycle\UpdateCycleRequest;

final readonly class UpdateCycleDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $code,
        public ?string $libelle,
        public ?string $niveauBacRequis,
        public ?int $dureeAnnees,
        public ?int $creditsTotal,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateCycleRequest $request): self
    {
        $v = $request->validated();
        return new self(
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            niveauBacRequis: $v['niveau_bac_requis'] ?? null,
            dureeAnnees: isset($v['duree_annees']) ? (int) $v['duree_annees'] : null,
            creditsTotal: isset($v['credits_total']) ? (int) $v['credits_total'] : null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->niveauBacRequis !== null) $data['niveau_bac_requis'] = $this->niveauBacRequis;
        if ($this->dureeAnnees !== null) $data['duree_annees'] = $this->dureeAnnees;
        if ($this->creditsTotal !== null) $data['credits_total'] = $this->creditsTotal;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
