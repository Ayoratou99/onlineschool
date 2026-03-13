<?php

namespace Modules\Document\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Document\Models\GeneratedDocument;

class GeneratedDocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_DOCUMENT_GENERE');
    }

    public function view(AuthorizableUser $user, GeneratedDocument $generatedDocument): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_DOCUMENT_GENERE');
    }
}
