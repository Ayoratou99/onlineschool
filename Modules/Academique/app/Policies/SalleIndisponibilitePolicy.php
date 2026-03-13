<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\SalleIndisponibilite;

class SalleIndisponibilitePolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_SALLE_INDISPOS'); } public function view(AuthorizableUser $user, SalleIndisponibilite $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_SALLE_INDISPO'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_SALLE_INDISPO'); } public function update(AuthorizableUser $user, SalleIndisponibilite $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_SALLE_INDISPO'); } public function delete(AuthorizableUser $user, SalleIndisponibilite $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_SALLE_INDISPO'); } }
