<?php

namespace Modules\Parametrage\Traits;

use App\Contracts\NiveauResolverInterface;

trait BelongsToExternalNiveau
{
    /**
     * Retourne le niveau via le contrat global (aucune référence à un autre module).
     */
    public function getNiveauAttribute(): ?object
    {
        $id = $this->getAttribute('niveau_id');

        return $id ? app(NiveauResolverInterface::class)->getNiveau($id) : null;
    }
}
