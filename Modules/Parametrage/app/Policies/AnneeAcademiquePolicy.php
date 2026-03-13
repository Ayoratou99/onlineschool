<?php

namespace Modules\Parametrage\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Parametrage\Models\AnneeAcademique;

class AnneeAcademiquePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_ANNEES_ACADEMIQUES');
    }

    public function view(AuthorizableUser $user, AnneeAcademique $anneeAcademique): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_ANNEE_ACADEMIQUE');
    }

    public function create(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_ANNEE_ACADEMIQUE');
    }

    public function update(AuthorizableUser $user, AnneeAcademique $anneeAcademique): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_ANNEE_ACADEMIQUE');
    }

    public function delete(AuthorizableUser $user, AnneeAcademique $anneeAcademique): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_ANNEE_ACADEMIQUE');
    }
}
