<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Etablissement;

class EtablissementPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_ETABLISSEMENTS');
    }

    public function view(AuthorizableUser $user, Etablissement $etablissement): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_ETABLISSEMENT');
    }

    public function create(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_ETABLISSEMENT');
    }

    public function update(AuthorizableUser $user, Etablissement $etablissement): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_ETABLISSEMENT');
    }

    public function delete(AuthorizableUser $user, Etablissement $etablissement): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_ETABLISSEMENT');
    }
}
