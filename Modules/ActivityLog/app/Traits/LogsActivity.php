<?php

namespace Modules\ActivityLog\Traits;

use App\Traits\LogsActivity as AppLogsActivity;
use Modules\ActivityLog\Models\ActivityLog;

trait LogsActivity
{
    use AppLogsActivity;

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject')->latest();
    }

    public function lastActivity()
    {
        return $this->morphOne(ActivityLog::class, 'subject')->latestOfMany();
    }
}
