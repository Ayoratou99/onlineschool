<?php

namespace App\Resolvers;

use App\Contracts\ModelResolverContract;
use Modules\Academique\Models\Etablissement;

/**
 * Resolver pour valider les références vers Etablissement (Academique).
 * Utilisé pour etablissement_id dans batiments, etc.
 */
class EtablissementResolver implements ModelResolverContract
{
    public function exists(string $uuid): bool
    {
        return Etablissement::where('id', $uuid)->exists();
    }

    public function findOrFail(string $uuid): Etablissement
    {
        return Etablissement::findOrFail($uuid);
    }
}
