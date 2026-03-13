<?php

namespace App\Contracts;

interface SemestreResolverInterface
{
    /**
     * Resolve a semestre by id. Returns null if not found.
     *
     * @return object|null
     */
    public function getSemestre(string $id): ?object;
}
