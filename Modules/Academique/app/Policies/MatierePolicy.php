<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Matiere;

class MatierePolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_MATIERES'); } public function view(AuthorizableUser $user, Matiere $m): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_MATIERE'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_MATIERE'); } public function update(AuthorizableUser $user, Matiere $m): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_MATIERE'); } public function delete(AuthorizableUser $user, Matiere $m): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_MATIERE'); } }
