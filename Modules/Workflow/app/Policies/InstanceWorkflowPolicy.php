<?php

namespace Modules\Workflow\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstanceWorkflowPolicy
{
    use HandlesAuthorization;

    /**
     * View a workflow instance (details, history, SVG, current step actions).
     */
    public function viewInstance(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_INSTANCE_WORKFLOW');
    }

    /**
     * Execute a transition on a workflow instance.
     */
    public function executeTransition(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('EXECUTER_TRANSITION_WORKFLOW');
    }

    /**
     * Suspend a workflow instance.
     */
    public function suspendWorkflow(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUSPENDRE_WORKFLOW');
    }

    /**
     * Resume a suspended workflow instance.
     */
    public function resumeWorkflow(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('REPRENDRE_WORKFLOW');
    }

    /**
     * Search workflow instances.
     */
    public function searchInstances(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('RECHERCHER_INSTANCES_WORKFLOW');
    }
}
