<?php

namespace App\Contracts;

interface UserResolverInterface
{
    /**
     * Resolve a user by id. Returns null if not found.
     *
     * @return object|null
     */
    public function getUser(string $id): ?object;
}
