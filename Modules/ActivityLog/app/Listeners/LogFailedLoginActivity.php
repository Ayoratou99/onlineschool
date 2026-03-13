<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Auth\Events\Failed;
use Modules\ActivityLog\Enums\ActivityAction;
use Modules\ActivityLog\Services\ActivityLogger;

class LogFailedLoginActivity
{
    public function __construct(protected ActivityLogger $logger) {}

    public function handle(Failed $event): void
    {
        $this->logger
            ->withTags(['auth', 'security', 'failed'])
            ->log(ActivityAction::FAILED_LOGIN, null, [
                'guard' => $event->guard,
                'credentials' => ['email' => $event->credentials['email'] ?? null],
            ]);
    }
}
