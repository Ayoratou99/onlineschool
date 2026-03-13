<?php

namespace Modules\Securite\DTOs\User;

use App\Contracts\ArrayableDTO;
use Modules\Securite\Http\Requests\UpdateUserRequest;

final readonly class UpdateUserDTO implements ArrayableDTO
{
    public function __construct(
        public ?string $nom,
        public ?string $prenom,
        public ?string $email,
        public ?string $state,
        public ?bool $twoFactorEnabled,
        /** @var array<string>|null */
        public ?array $roles,
    ) {}

    public static function fromRequest(UpdateUserRequest $request): self
    {
        $v = $request->validated();

        return new self(
            nom: $v['nom'] ?? null,
            prenom: array_key_exists('prenom', $v) ? $v['prenom'] : null,
            email: $v['email'] ?? null,
            state: $v['state'] ?? null,
            twoFactorEnabled: isset($v['two_factor_enabled']) ? (bool) $v['two_factor_enabled'] : null,
            roles: $v['roles'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->nom !== null) {
            $data['nom'] = $this->nom;
        }
        if ($this->prenom !== null) {
            $data['prenom'] = $this->prenom;
        }
        if ($this->email !== null) {
            $data['email'] = $this->email;
        }
        if ($this->state !== null) {
            $data['state'] = $this->state;
        }
        if ($this->twoFactorEnabled !== null) {
            $data['two_factor_enabled'] = $this->twoFactorEnabled;
        }
        if ($this->roles !== null) {
            $data['roles'] = $this->roles;
        }

        return $data;
    }
}
