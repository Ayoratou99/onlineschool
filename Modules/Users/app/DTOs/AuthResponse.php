<?php

namespace Modules\Users\DTOs;

class AuthResponse
{
    public function __construct(
        public string $user_uuid,
        public string $token,
    ) {}

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'user_uuid' => $this->user_uuid,
            'token' => $this->token,
        ];
    }
}
