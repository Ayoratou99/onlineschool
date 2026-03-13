<?php

namespace Modules\ActivityLog\Providers;

use App\Contracts\AuditLoggerInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\ActivityLog\Observers\ModelActivityObserver;
use Modules\ActivityLog\Policies\ActivityLogPolicy;
use Modules\ActivityLog\Services\ActivityLogger;
use Modules\ActivityLog\Services\AuditLogger;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->singleton(ActivityLogger::class, fn () => new ActivityLogger());
        $this->app->singleton(AuditLoggerInterface::class, AuditLogger::class);
    }

    public function boot(): void
    {
        // Migrations run on tenant DBs only (database/migrations/tenant/), not on central.
        $this->mergeConfigFrom(module_path('ActivityLog', 'config/config.php'), 'activitylog');
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);

        foreach (config('activitylog.audited_models', []) as $modelClass) {
            if (is_string($modelClass) && class_exists($modelClass)) {
                $modelClass::observe(ModelActivityObserver::class);
            }
        }
    }
}
