<?php

namespace Modules\Securite\Services;

use App\Contracts\UserExistsCheckerInterface;
use Modules\Securite\Models\User;

class UserExistsChecker implements UserExistsCheckerInterface
{
    public function exists(string $id): bool
    {
        return User::whereKey($id)->exists();
    }
}
