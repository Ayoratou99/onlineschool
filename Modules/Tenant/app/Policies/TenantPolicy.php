<?php

namespace Modules\Tenant\Policies;

use App\Contracts\AuthorizableUser;
use Modules\Tenant\Models\Tenant;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN');
    }

    public function view(AuthorizableUser $user, Tenant $tenant): bool
    {
        return $user->hasRole('ADMIN');
    }

    public function create(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN');
    }

    public function update(AuthorizableUser $user, Tenant $tenant): bool
    {
        return $user->hasRole('ADMIN');
    }

    public function delete(AuthorizableUser $user, Tenant $tenant): bool
    {
        return $user->hasRole('ADMIN');
    }

    public function clean(AuthorizableUser $user, Tenant $tenant): bool
    {
        return $user->hasRole('ADMIN');
    }
}
