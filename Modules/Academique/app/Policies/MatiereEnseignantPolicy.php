<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\MatiereEnseignant;

class MatiereEnseignantPolicy { use HandlesAuthorization; public function viewAny(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_MATIERE_ENSEIGNANTS'); } public function view(AuthorizableUser $user, MatiereEnseignant $m): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_MATIERE_ENSEIGNANT'); } public function create(AuthorizableUser $user): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_MATIERE_ENSEIGNANT'); } public function update(AuthorizableUser $user, MatiereEnseignant $m): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_MATIERE_ENSEIGNANT'); } public function delete(AuthorizableUser $user, MatiereEnseignant $m): bool { return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_MATIERE_ENSEIGNANT'); } }
