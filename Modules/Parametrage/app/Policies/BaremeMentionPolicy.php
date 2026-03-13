<?php

namespace Modules\Parametrage\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Parametrage\Models\BaremeMention;

class BaremeMentionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_BAREMES_MENTION');
    }

    public function view(AuthorizableUser $user, BaremeMention $baremeMention): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_BAREME_MENTION');
    }

    public function create(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_BAREME_MENTION');
    }

    public function update(AuthorizableUser $user, BaremeMention $baremeMention): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_BAREME_MENTION');
    }

    public function delete(AuthorizableUser $user, BaremeMention $baremeMention): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_BAREME_MENTION');
    }
}
