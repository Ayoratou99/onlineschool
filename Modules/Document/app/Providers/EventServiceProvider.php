<?php

namespace Modules\Document\Providers;

use Ayoratoumvone\Documentgeneratorx\Events\DocumentGenerated as PackageDocumentGenerated;
use Ayoratoumvone\Documentgeneratorx\Events\DocumentGenerationFailed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Document\Listeners\OnPackageDocumentGenerated;
use Modules\Document\Listeners\OnPackageDocumentGenerationFailed;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PackageDocumentGenerated::class => [OnPackageDocumentGenerated::class],
        DocumentGenerationFailed::class => [OnPackageDocumentGenerationFailed::class],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
