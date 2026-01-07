<?php

namespace Modules\Users\Interfaces;

use Modules\Users\DTOs\AuthResponse;
use Modules\Users\Models\User;

interface AuthInterface
{
    public function login(string $email, string $password): AuthResponse;

    public function logout(): void;

    public function register(string $email, string $password): ?User;

    public function forgotPassword(string $email): void;

    public function resetPassword(string $email, string $password): void;
}
