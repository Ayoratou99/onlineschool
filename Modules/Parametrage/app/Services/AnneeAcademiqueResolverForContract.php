<?php

namespace Modules\Parametrage\Services;

use App\Contracts\AnneeAcademiqueResolverInterface;
use Modules\Parametrage\Models\AnneeAcademique;

class AnneeAcademiqueResolverForContract implements AnneeAcademiqueResolverInterface
{
    public function getAnneeAcademique(string $id): ?object
    {
        return AnneeAcademique::find($id);
    }
}
