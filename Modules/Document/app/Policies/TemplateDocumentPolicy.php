<?php

namespace Modules\Document\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Document\Models\TemplateDocument;

class TemplateDocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_TEMPLATES_DOCUMENTS');
    }

    public function view(AuthorizableUser $user, TemplateDocument $templateDocument): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_TEMPLATE_DOCUMENT');
    }

    public function create(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_TEMPLATE_DOCUMENT');
    }

    public function update(AuthorizableUser $user, TemplateDocument $templateDocument): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_TEMPLATE_DOCUMENT');
    }

    public function delete(AuthorizableUser $user, TemplateDocument $templateDocument): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_TEMPLATE_DOCUMENT');
    }
}
