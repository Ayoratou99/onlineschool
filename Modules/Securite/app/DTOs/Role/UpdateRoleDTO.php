<?php

namespace Modules\Securite\DTOs\Role;

use App\Contracts\ArrayableDTO;
use Modules\Securite\Http\Requests\UpdateRoleRequest;

final readonly class UpdateRoleDTO implements ArrayableDTO
{
    /**
     * @param array<string>|null $permissions
     */
    public function __construct(
        public ?string $name,
        public ?string $description,
        public ?string $state,
        public ?array $permissions,
    ) {}

    public static function fromRequest(UpdateRoleRequest $request): self
    {
        $v = $request->validated();

        return new self(
            name: $v['name'] ?? null,
            description: array_key_exists('description', $v) ? $v['description'] : null,
            state: $v['state'] ?? null,
            permissions: $v['permissions'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->name !== null) {
            $data['name'] = $this->name;
        }
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        if ($this->state !== null) {
            $data['state'] = $this->state;
        }
        if ($this->permissions !== null) {
            $data['permissions'] = $this->permissions;
        }

        return $data;
    }
}
