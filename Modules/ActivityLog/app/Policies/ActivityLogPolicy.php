<?php

namespace Modules\ActivityLog\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ActivityLog\Models\ActivityLog;

class ActivityLogPolicy
{
    use HandlesAuthorization;

    public function __construct() {}

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_JOURNAUX_ACTIVITE');
    }

    public function view(AuthorizableUser $user, ActivityLog $activityLog): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_JOURNAL_ACTIVITE');
    }
}
