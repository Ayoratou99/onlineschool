<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Etage;

class EtagePolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_ETAGES'); } public function view(AuthorizableUser $user, Etage $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_ETAGE'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_ETAGE'); } public function update(AuthorizableUser $user, Etage $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_ETAGE'); } public function delete(AuthorizableUser $user, Etage $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_ETAGE'); } }
