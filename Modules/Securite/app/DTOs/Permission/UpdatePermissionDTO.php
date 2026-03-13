<?php

namespace Modules\Securite\DTOs\Permission;

use App\Contracts\ArrayableDTO;
use Modules\Securite\Http\Requests\Permission\UpdatePermissionRequest;

final readonly class UpdatePermissionDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $name,
        public ?string $description,
        public ?string $state,
    ) {}

    public static function fromRequest(UpdatePermissionRequest $request): self
    {
        $v = $request->validated();
        return new self(
            name: $v['name'] ?? null,
            description: $v['description'] ?? null,
            state: $v['state'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->name !== null) $data['name'] = $this->name;
        if ($this->description !== null) $data['description'] = $this->description;
        if ($this->state !== null) $data['state'] = $this->state;
        return $data;
    }
}
