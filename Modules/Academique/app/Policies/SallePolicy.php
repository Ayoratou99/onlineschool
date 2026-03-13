<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Salle;

class SallePolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_SALLES'); } public function view(AuthorizableUser $user, Salle $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_SALLE'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_SALLE'); } public function update(AuthorizableUser $user, Salle $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_SALLE'); } public function delete(AuthorizableUser $user, Salle $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_SALLE'); } }
