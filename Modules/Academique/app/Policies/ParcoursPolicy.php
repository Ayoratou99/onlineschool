<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Parcours;

class ParcoursPolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_PARCOURS'); } public function view(AuthorizableUser $user, Parcours $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_PARCOURS'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_PARCOURS'); } public function update(AuthorizableUser $user, Parcours $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_PARCOURS'); } public function delete(AuthorizableUser $user, Parcours $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_PARCOURS'); } }
