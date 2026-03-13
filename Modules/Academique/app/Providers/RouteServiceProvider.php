<?php

namespace Modules\Academique\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Academique';

    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        $webPath = module_path($this->name, '/routes/web.php');
        if (file_exists($webPath)) {
            Route::middleware('web')->group($webPath);
        }
    }
}
