<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\ProgrammeDetail;

class ProgrammeDetailPolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_PROGRAMME_DETAILS'); } public function view(AuthorizableUser $user, ProgrammeDetail $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_PROGRAMME_DETAIL'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_PROGRAMME_DETAIL'); } public function update(AuthorizableUser $user, ProgrammeDetail $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_PROGRAMME_DETAIL'); } public function delete(AuthorizableUser $user, ProgrammeDetail $p): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_PROGRAMME_DETAIL'); } }
