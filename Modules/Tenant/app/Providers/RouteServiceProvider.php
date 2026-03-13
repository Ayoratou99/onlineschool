<?php

namespace Modules\Tenant\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Tenant';

    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        // API routes are loaded centrally (central domains only) via bootstrap/app.php
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        $webPath = dirname(__DIR__, 2) . '/routes/web.php';
        if (file_exists($webPath)) {
            Route::middleware('web')->group($webPath);
        }
    }
}
