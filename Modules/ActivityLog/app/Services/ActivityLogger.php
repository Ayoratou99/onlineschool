<?php

namespace Modules\ActivityLog\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\ActivityLog\Enums\ActivityAction;
use Modules\ActivityLog\Models\ActivityLog;

class ActivityLogger
{
    protected ?string $batchId = null;
    protected array $properties = [];
    protected array $tags = [];
    protected ?string $description = null;

    public function log(ActivityAction|string $action, ?Model $subject = null, ?array $properties = null): ?ActivityLog
    {
        if (!config('activitylog.enabled', true)) {
            return null;
        }

        try {
            $user = auth()->user();
            $actionValue = $action instanceof ActivityAction ? $action->value : $action;

            $properties = array_merge($this->properties, $properties ?? []);
            $entity = $subject ? Str::snake(class_basename($subject)) : ($properties['entity'] ?? null);

            $logData = [
                'user_id' => $user?->id,
                'user_type' => $user ? get_class($user) : null,
                'user_name' => $user?->nom ?? $user?->name ?? null,
                'user_email' => $user?->email,
                'action' => $actionValue,
                'entity' => $entity,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject?->id,
                'subject_name' => $this->resolveSubjectName($subject),
                'properties' => $properties,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'method' => request()->method(),
                'url' => request()->fullUrl(),
                'request_data' => $this->sanitize(request()->except(config('activitylog.sensitive_fields', []))),
                'description' => $this->description,
                'tags' => $this->tags,
                'batch_id' => $this->batchId,
            ];

            $log = ActivityLog::create($logData);
            $this->reset();

            return $log;
        } catch (\Throwable) {
            return null;
        }
    }

    public function logModelChanges(Model $model, ActivityAction $action, ?array $oldValues = null): ?ActivityLog
    {
        $ignored = $this->getIgnoredAttributes($model);

        $old = array_diff_key($oldValues ?? $model->getOriginal(), array_flip($ignored));
        $new = array_diff_key($model->getDirty(), array_flip($ignored));

        return $this->log($action, $model, [
            'old_values' => $old,
            'new_values' => $new,
        ]);
    }

    public function withProperties(array $properties): self
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    public function withTags(array $tags): self
    {
        $this->tags = array_merge($this->tags, $tags);
        return $this;
    }

    public function withDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function startBatch(): self
    {
        $this->batchId = Str::uuid()->toString();
        return $this;
    }

    public function endBatch(): void
    {
        $this->batchId = null;
    }

    protected function reset(): void
    {
        $this->properties = [];
        $this->tags = [];
        $this->description = null;
    }

    protected function resolveSubjectName(?Model $subject): ?string
    {
        if (!$subject) {
            return null;
        }

        return $subject->name
            ?? $subject->nom
            ?? $subject->title
            ?? $subject->email
            ?? $subject->reference
            ?? "#{$subject->id}";
    }

    protected function getIgnoredAttributes(Model $model): array
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

    protected function sanitize(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_string($value) && strlen($value) > 1000) {
                $sanitized[$key] = substr($value, 0, 1000) . '...';
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
}
