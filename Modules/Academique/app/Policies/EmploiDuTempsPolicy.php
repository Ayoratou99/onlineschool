<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\EmploiDuTemps;

class EmploiDuTempsPolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_EMPLOIS_DU_TEMPS'); } public function view(AuthorizableUser $user, EmploiDuTemps $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_EMPLOI_DU_TEMPS'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_EMPLOI_DU_TEMPS'); } public function update(AuthorizableUser $user, EmploiDuTemps $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_EMPLOI_DU_TEMPS'); } public function delete(AuthorizableUser $user, EmploiDuTemps $e): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_EMPLOI_DU_TEMPS'); } }
