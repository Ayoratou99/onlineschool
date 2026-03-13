<?php

namespace Modules\Academique\DTOs\Batiment;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Batiment\UpdateBatimentRequest;

final readonly class UpdateBatimentDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $etablissementId,
        public ?string $code,
        public ?string $libelle,
        public ?string $adresse,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateBatimentRequest $request): self
    {
        $v = $request->validated();
        return new self(
            etablissementId: $v['etablissement_id'] ?? null,
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            adresse: $v['adresse'] ?? null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->etablissementId !== null) $data['etablissement_id'] = $this->etablissementId;
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->adresse !== null) $data['adresse'] = $this->adresse;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
