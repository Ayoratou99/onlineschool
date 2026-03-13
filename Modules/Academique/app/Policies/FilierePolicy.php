<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Filiere;

class FilierePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_FILIERES'); }
    public function view(AuthorizableUser $user, Filiere $filiere): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_FILIERE'); }
    public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_FILIERE'); }
    public function update(AuthorizableUser $user, Filiere $filiere): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_FILIERE'); }
    public function delete(AuthorizableUser $user, Filiere $filiere): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_FILIERE'); }
}
