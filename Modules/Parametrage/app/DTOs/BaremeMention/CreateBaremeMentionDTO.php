<?php

namespace Modules\Parametrage\DTOs\BaremeMention;

use App\Contracts\ArrayableDTO;
use Modules\Parametrage\Http\Requests\BaremeMention\StoreBaremeMentionRequest;

final readonly class CreateBaremeMentionDTO implements ArrayableDTO
{
    public function __construct(
        public string $anneeAcademiqueId,
        public string $mention,
        public string $baremeMin,
        public string $baremeMax,
        public int $ordre,
    ) {}

    public static function fromRequest(StoreBaremeMentionRequest $request): self
    {
        return new self(
            anneeAcademiqueId: $request->validated('annee_academique_id'),
            mention: $request->validated('mention'),
            baremeMin: $request->validated('bareme_min'),
            baremeMax: $request->validated('bareme_max'),
            ordre: (int) $request->validated('ordre', 0),
        );
    }

    public function toArray(): array
    {
        return [
            'annee_academique_id' => $this->anneeAcademiqueId,
            'mention' => $this->mention,
            'bareme_min' => $this->baremeMin,
            'bareme_max' => $this->baremeMax,
            'ordre' => $this->ordre,
        ];
    }
}
