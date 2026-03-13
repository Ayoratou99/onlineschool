<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\EmploiDuTempsException;

class EmploiDuTempsExceptionPolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_EDT_EXCEPTIONS'); } public function view(AuthorizableUser $user, EmploiDuTempsException $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_EDT_EXCEPTION'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_EDT_EXCEPTION'); } public function update(AuthorizableUser $user, EmploiDuTempsException $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_EDT_EXCEPTION'); } public function delete(AuthorizableUser $user, EmploiDuTempsException $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_EDT_EXCEPTION'); } }
