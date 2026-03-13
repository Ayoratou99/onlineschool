<?php

namespace Modules\Securite\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Securite\Models\User;

class UserCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user
    ) {}
}
