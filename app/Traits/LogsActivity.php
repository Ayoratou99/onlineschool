<?php

namespace App\Traits;

trait LogsActivity
{
    protected static bool $activityLogEnabled = true;

    public static function getActivityActions(): array
    {
        return static::$logActions ?? config('activitylog.default_actions', ['created', 'updated', 'deleted']);
    }

    public static function getIgnoredActivityAttributes(): array
    {
        return static::$ignoreActivityAttributes ?? [];
    }

    public static function isActivityLogEnabled(): bool
    {
        return static::$activityLogEnabled;
    }

    public static function withoutActivityLog(callable $callback)
    {
        static::$activityLogEnabled = false;
        try {
            return $callback();
        } finally {
            static::$activityLogEnabled = true;
        }
    }
}
