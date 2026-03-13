<?php

namespace App\Resolvers;

use App\Contracts\ModelResolverContract;
use Modules\Securite\Models\User;

/**
 * Resolver pour valider les références vers User (Securite) dans les FormRequests.
 * Utilisé pour responsable_id, enseignant_id, created_by, valide_par, etc.
 */
class UserResolver implements ModelResolverContract
{
    public function exists(string $uuid): bool
    {
        return User::where('id', $uuid)->exists();
    }

    public function findOrFail(string $uuid): User
    {
        return User::findOrFail($uuid);
    }
}
