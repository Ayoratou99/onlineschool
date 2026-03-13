<?php

namespace App\Contracts;

interface NiveauResolverInterface
{
    /**
     * Resolve a niveau by id. Returns null if not found.
     *
     * @return object|null
     */
    public function getNiveau(string $id): ?object;
}
