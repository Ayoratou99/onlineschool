<?php

namespace Modules\Parametrage\Traits;

use App\Contracts\SemestreResolverInterface;

trait BelongsToExternalSemestre
{
    /**
     * Retourne le semestre via le contrat global (aucune référence à un autre module).
     */
    public function getSemestreAttribute(): ?object
    {
        $id = $this->getAttribute('semestre_id');

        return $id ? app(SemestreResolverInterface::class)->getSemestre($id) : null;
    }
}
