<?php

namespace Modules\Securite\Services;

use App\Contracts\UserResolverInterface;
use Modules\Securite\Models\User;

class UserResolver implements UserResolverInterface
{
    public function getUser(string $id): ?object
    {
        return User::find($id);
    }
}
