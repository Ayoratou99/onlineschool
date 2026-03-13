<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Auth\Events\Login;
use Modules\ActivityLog\Enums\ActivityAction;
use Modules\ActivityLog\Services\ActivityLogger;

class LogAuthenticatedActivity
{
    public function __construct(protected ActivityLogger $logger) {}

    public function handle(Login $event): void
    {
        $this->logger
            ->withTags(['auth', 'security'])
            ->log(ActivityAction::AUTHENTICATED, $event->user, [
                'guard' => $event->guard,
                'remember' => $event->remember ?? false,
            ]);
    }
}
