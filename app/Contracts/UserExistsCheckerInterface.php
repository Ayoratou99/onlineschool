<?php

namespace App\Contracts;

interface UserExistsCheckerInterface
{
    public function exists(string $id): bool;
}
