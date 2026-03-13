<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Niveau;

class NiveauPolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_NIVEAUX'); } public function view(AuthorizableUser $user, Niveau $n): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_NIVEAU'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_NIVEAU'); } public function update(AuthorizableUser $user, Niveau $n): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_NIVEAU'); } public function delete(AuthorizableUser $user, Niveau $n): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_NIVEAU'); } }
