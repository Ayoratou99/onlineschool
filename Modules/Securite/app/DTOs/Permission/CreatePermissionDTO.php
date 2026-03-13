<?php

namespace Modules\Securite\DTOs\Permission;

use App\Contracts\ArrayableDTO;
use Modules\Securite\Http\Requests\Permission\StorePermissionRequest;

final readonly class CreatePermissionDTO implements ArrayableDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public string $state,
    ) {}

    public static function fromRequest(StorePermissionRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            description: $request->validated('description'),
            state: $request->validated('state'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'state' => $this->state,
        ];
    }
}
