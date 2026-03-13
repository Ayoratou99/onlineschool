<?php

namespace Modules\Securite\DTOs\User;

use App\Contracts\ArrayableDTO;
use Modules\Securite\Http\Requests\StoreUserRequest;

final readonly class CreateUserDTO implements ArrayableDTO
{
    public function __construct(
        public string $nom,
        public ?string $prenom,
        public string $email,
        public string $state,
        public bool $twoFactorEnabled,
        /** @var array<string>|null */
        public ?array $roles,
    ) {}

    public static function fromRequest(StoreUserRequest $request): self
    {
        $v = $request->validated();

        return new self(
            nom: $v['nom'],
            prenom: $v['prenom'] ?? null,
            email: $v['email'],
            state: $v['state'] ?? 'ACTIVE',
            twoFactorEnabled: (bool) ($v['two_factor_enabled'] ?? false),
            roles: $v['roles'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'state' => $this->state,
            'two_factor_enabled' => $this->twoFactorEnabled,
            'roles' => $this->roles,
        ];
    }
}
