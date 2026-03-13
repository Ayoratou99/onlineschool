<?php

namespace App\Contracts;

/**
 * Interface pour les résolveurs de modèles externes.
 * Chaque module peut exposer des résolveurs pour valider les clés étrangères
 * vers d'autres modules (validation dans FormRequest sans contrainte FK en base).
 */
interface ModelResolverContract
{
    public function exists(string $uuid): bool;

    public function findOrFail(string $uuid): mixed;
}
