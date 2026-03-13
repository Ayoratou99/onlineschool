<?php

namespace Modules\ActivityLog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Modules\ActivityLog\Listeners\LogAuthenticatedActivity;
use Modules\ActivityLog\Listeners\LogLogoutActivity;
use Modules\ActivityLog\Listeners\LogFailedLoginActivity;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [LogAuthenticatedActivity::class],
        Logout::class => [LogLogoutActivity::class],
        Failed::class => [LogFailedLoginActivity::class],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
