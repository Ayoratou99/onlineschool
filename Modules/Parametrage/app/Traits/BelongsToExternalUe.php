<?php

namespace Modules\Parametrage\Traits;

use App\Contracts\UeResolverInterface;

trait BelongsToExternalUe
{
    /**
     * Retourne l'UE via le contrat global (aucune référence à un autre module).
     */
    public function getUeAttribute(): ?object
    {
        $id = $this->getAttribute('ue_id');

        return $id ? app(UeResolverInterface::class)->getUe($id) : null;
    }
}
