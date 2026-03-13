<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Programme;

class ProgrammePolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_PROGRAMMES'); } public function view(AuthorizableUser $user, Programme $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_PROGRAMME'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_PROGRAMME'); } public function update(AuthorizableUser $user, Programme $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_PROGRAMME'); } public function delete(AuthorizableUser $user, Programme $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_PROGRAMME'); } }
