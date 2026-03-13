<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\UniteEnseignement;

class UniteEnseignementPolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_UE'); } public function view(AuthorizableUser $user, UniteEnseignement $u): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_UE'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_UE'); } public function update(AuthorizableUser $user, UniteEnseignement $u): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_UE'); } public function delete(AuthorizableUser $user, UniteEnseignement $u): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_UE'); } }
