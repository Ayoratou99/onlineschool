<?php

namespace Modules\Parametrage\Traits;

use App\Contracts\UserResolverInterface;

trait BelongsToExternalUser
{
    /**
     * Retourne l'utilisateur créateur via le contrat global (aucune référence à un autre module).
     */
    public function createdBy(): ?object
    {
        $id = $this->getAttribute('created_by');

        return $id ? app(UserResolverInterface::class)->getUser($id) : null;
    }
}
