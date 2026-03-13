<?php

namespace Modules\ActivityLog\Observers;

use Illuminate\Database\Eloquent\Model;
use Modules\ActivityLog\Enums\ActivityAction;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\ActivityLog\Services\ActivityLogger;

class ModelActivityObserver
{
    public function __construct(protected ActivityLogger $logger) {}

    public function created(Model $model): void
    {
        if (!$this->shouldLog($model, 'created')) {
            return;
        }

        $ignored = $this->getIgnored($model);
        $attributes = array_diff_key($model->getAttributes(), array_flip($ignored));

        $this->logger->log(ActivityAction::CREATED, $model, [
            'attributes' => $attributes,
        ]);
    }

    public function updated(Model $model): void
    {
        if (!$this->shouldLog($model, 'updated')) {
            return;
        }

        $ignored = $this->getIgnored($model);
        $dirty = array_diff_key($model->getDirty(), array_flip($ignored));

        if (empty($dirty)) {
            return;
        }

        $this->logger->logModelChanges($model, ActivityAction::UPDATED);
    }

    public function deleted(Model $model): void
    {
        if (!$this->shouldLog($model, 'deleted')) {
            return;
        }

        $action = method_exists($model, 'isForceDeleting') && $model->isForceDeleting()
            ? ActivityAction::FORCE_DELETED
            : ActivityAction::DELETED;

        $this->logger->log($action, $model, [
            'attributes' => $model->getAttributes(),
        ]);
    }

    public function restored(Model $model): void
    {
        if (!$this->shouldLog($model, 'restored')) {
            return;
        }

        $this->logger->log(ActivityAction::RESTORED, $model);
    }

    protected function shouldLog(Model $model, string $event): bool
    {
        if (!config('activitylog.enabled', true)) {
            return false;
        }

        if ($model instanceof ActivityLog) {
            return false;
        }

        $excluded = config('activitylog.exclude_models', []);
        if (in_array(get_class($model), $excluded, true)) {
            return false;
        }

        if (method_exists($model, 'isActivityLogEnabled') && !$model::isActivityLogEnabled()) {
            return false;
        }

        if (method_exists($model, 'getActivityActions')) {
            return in_array($event, $model::getActivityActions());
        }

        $defaultActions = config('activitylog.default_actions', ['created', 'updated', 'deleted']);
        return in_array($event, $defaultActions);
    }

    protected function getIgnored(Model $model): array
    {
        $defaults = config('activitylog.ignored_attributes', ['updated_at']);

        if (method_exists($model, 'getIgnoredActivityAttributes')) {
            return array_merge($defaults, $model::getIgnoredActivityAttributes());
        }

        if (property_exists($model, 'ignoreActivityAttributes')) {
            return array_merge($defaults, $model::$ignoreActivityAttributes);
        }

        return $defaults;
    }
}
