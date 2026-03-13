<?php

namespace Modules\Parametrage\DTOs\BaremeMention;

use App\Contracts\ArrayableDTO;
use Modules\Parametrage\Http\Requests\BaremeMention\UpdateBaremeMentionRequest;

final readonly class UpdateBaremeMentionDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $mention,
        public ?string $baremeMin,
        public ?string $baremeMax,
        public ?int $ordre,
    ) {}

    public static function fromRequest(UpdateBaremeMentionRequest $request): self
    {
        $validated = $request->validated();
        return new self(
            mention: $validated['mention'] ?? null,
            baremeMin: isset($validated['bareme_min']) ? (string) $validated['bareme_min'] : null,
            baremeMax: isset($validated['bareme_max']) ? (string) $validated['bareme_max'] : null,
            ordre: isset($validated['ordre']) ? (int) $validated['ordre'] : null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->mention !== null) {
            $data['mention'] = $this->mention;
        }
        if ($this->baremeMin !== null) {
            $data['bareme_min'] = $this->baremeMin;
        }
        if ($this->baremeMax !== null) {
            $data['bareme_max'] = $this->baremeMax;
        }
        if ($this->ordre !== null) {
            $data['ordre'] = $this->ordre;
        }
        return $data;
    }
}
