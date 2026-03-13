<?php

namespace App\Resolvers;

use App\Contracts\ModelResolverContract;
use Modules\Parametrage\Models\AnneeAcademique;

class AnneeAcademiqueResolver implements ModelResolverContract
{
    public function exists(string $uuid): bool
    {
        return AnneeAcademique::where('id', $uuid)->exists();
    }

    public function findOrFail(string $uuid): AnneeAcademique
    {
        return AnneeAcademique::findOrFail($uuid);
    }
}
