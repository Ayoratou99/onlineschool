<?php

namespace App\Contracts;

interface AnneeAcademiqueResolverInterface
{
    /**
     * Resolve an année académique by id. Returns null if not found.
     *
     * @return object|null
     */
    public function getAnneeAcademique(string $id): ?object;
}
