<?php

namespace App\Providers;

use App\Contracts\DocumentManagementInterface;
use App\Services\PaperlessService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            DocumentManagementInterface::class,
            PaperlessService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
