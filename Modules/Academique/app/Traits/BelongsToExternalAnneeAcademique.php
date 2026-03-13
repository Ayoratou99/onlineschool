<?php

namespace Modules\Academique\Traits;

use App\Contracts\AnneeAcademiqueResolverInterface;

trait BelongsToExternalAnneeAcademique
{
    public function getAnneeAcademiqueAttribute(): ?object
    {
        $id = $this->getAttribute('annee_academique_id');

        return $id ? app(AnneeAcademiqueResolverInterface::class)->getAnneeAcademique($id) : null;
    }
}
