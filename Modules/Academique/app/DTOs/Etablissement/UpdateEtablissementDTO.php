<?php

namespace Modules\Academique\DTOs\Etablissement;

use App\Contracts\ArrayableDTO;
use Modules\Academique\Http\Requests\Etablissement\UpdateEtablissementRequest;

final readonly class UpdateEtablissementDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $code,
        public ?string $libelle,
        public ?bool $isActive,
    ) {}

    public static function fromRequest(UpdateEtablissementRequest $request): self
    {
        $v = $request->validated();
        return new self(
            code: $v['code'] ?? null,
            libelle: $v['libelle'] ?? null,
            isActive: isset($v['is_active']) ? (bool) $v['is_active'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->code !== null) $data['code'] = $this->code;
        if ($this->libelle !== null) $data['libelle'] = $this->libelle;
        if ($this->isActive !== null) $data['is_active'] = $this->isActive;
        return $data;
    }
}
