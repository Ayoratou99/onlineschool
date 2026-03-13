<?php

namespace Modules\Academique\Traits;

use App\Contracts\UserResolverInterface;

trait BelongsToExternalUser
{
    public function getResponsableAttribute(): ?object
    {
        $id = $this->getAttribute('responsable_id');

        return $id ? app(UserResolverInterface::class)->getUser($id) : null;
    }

    public function getEnseignantAttribute(): ?object
    {
        $id = $this->getAttribute('enseignant_id');

        return $id ? app(UserResolverInterface::class)->getUser($id) : null;
    }

    public function getValideParAttribute(): ?object
    {
        $id = $this->getAttribute('valide_par');

        return $id ? app(UserResolverInterface::class)->getUser($id) : null;
    }

    public function getCreatedByAttribute(): ?object
    {
        $id = $this->getAttribute('created_by');

        return $id ? app(UserResolverInterface::class)->getUser($id) : null;
    }

    public function getNouvelEnseignantAttribute(): ?object
    {
        $id = $this->getAttribute('nouvel_enseignant_id');

        return $id ? app(UserResolverInterface::class)->getUser($id) : null;
    }
}
