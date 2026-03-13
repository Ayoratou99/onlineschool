<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Groupe;

class GroupePolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_GROUPES'); } public function view(AuthorizableUser $user, Groupe $g): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_GROUPE'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_GROUPE'); } public function update(AuthorizableUser $user, Groupe $g): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_GROUPE'); } public function delete(AuthorizableUser $user, Groupe $g): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_GROUPE'); } }
