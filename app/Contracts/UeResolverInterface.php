<?php

namespace App\Contracts;

interface UeResolverInterface
{
    /**
     * Resolve a UE by id. Returns null if not found.
     *
     * @return object|null
     */
    public function getUe(string $id): ?object;
}
