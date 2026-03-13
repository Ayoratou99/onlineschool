<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Auth\Events\Logout;
use Modules\ActivityLog\Enums\ActivityAction;
use Modules\ActivityLog\Services\ActivityLogger;

class LogLogoutActivity
{
    public function __construct(protected ActivityLogger $logger) {}

    public function handle(Logout $event): void
    {
        $this->logger
            ->withTags(['auth', 'security'])
            ->log(ActivityAction::LOGOUT, $event->user, ['guard' => $event->guard]);
    }
}
