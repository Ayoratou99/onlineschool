<?php

namespace Modules\Securite\DTOs\Role;

use App\Contracts\ArrayableDTO;
use Modules\Securite\Http\Requests\StoreRoleRequest;

final readonly class CreateRoleDTO implements ArrayableDTO
{
    /**
     * @param array<string>|null $permissions
     */
    public function __construct(
        public string $name,
        public ?string $description,
        public string $state,
        public ?array $permissions,
    ) {}

    public static function fromRequest(StoreRoleRequest $request): self
    {
        $v = $request->validated();

        return new self(
            name: $v['name'],
            description: $v['description'] ?? null,
            state: $v['state'],
            permissions: $v['permissions'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'state' => $this->state,
            'permissions' => $this->permissions,
        ];
    }
}
