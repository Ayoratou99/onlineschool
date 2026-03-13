<?php

namespace Modules\Securite\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Securite\Events\UserCreated;
use Modules\Securite\Listeners\SendUserVerificationEmail;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserCreated::class => [
            SendUserVerificationEmail::class,
        ],
    ];

    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
