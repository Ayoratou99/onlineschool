<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Semestre;

class SemestrePolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_SEMESTRES'); } public function view(AuthorizableUser $user, Semestre $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_SEMESTRE'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_SEMESTRE'); } public function update(AuthorizableUser $user, Semestre $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_SEMESTRE'); } public function delete(AuthorizableUser $user, Semestre $s): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_SEMESTRE'); } }
