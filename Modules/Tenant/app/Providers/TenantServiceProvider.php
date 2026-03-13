<?php

namespace Modules\Tenant\Providers;

use Modules\Tenant\Models\Tenant as TenantModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Tenant\Policies\TenantPolicy;

class TenantServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/config.php',
            'tenant'
        );
    }

    protected function registerPolicies(): void
    {
        Gate::policy(TenantModel::class, TenantPolicy::class);
    }
}
