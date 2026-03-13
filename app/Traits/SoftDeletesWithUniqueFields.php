<?php

namespace App\Traits;

trait SoftDeletesWithUniqueFields
{
    protected static function bootSoftDeletesWithUniqueFields()
    {
        static::deleting(function ($model) {
            if (!$model->isForceDeleting()) {
                $uniqueFields = $model->getUniqueFields();

                foreach ($uniqueFields as $field) {
                    $value = $model->$field;
                    if (!empty($value) && !$model->isUuid($value)) {
                        $model->$field = $model->generateUniqueDeletedValue($field, $value);
                    }
                }

                $model->saveQuietly();
            }
        });

        static::restoring(function ($model) {
            $uniqueFields = $model->getUniqueFields();

            foreach ($uniqueFields as $field) {
                $value = $model->$field;
                if (!empty($value) && !$model->isUuid($value)) {
                    $model->$field = $model->restoreOriginalValue($field, $value);
                }
            }
        });
    }

    protected function generateUniqueDeletedValue(string $field, string $value): string
    {
        $counter = 1;
        $baseValue = preg_replace('/-deleted-\d+$/', '', $value);
        $newValue = $baseValue . '-deleted-' . $counter;
        while (static::withTrashed()->where($field, $newValue)->exists()) {
            $counter++;
            $newValue = $baseValue . '-deleted-' . $counter;
        }

        return $newValue;
    }

    protected function restoreOriginalValue(string $field, string $value): string
    {
        return preg_replace('/-deleted-\d+$/', '', $value);
    }

    protected function isUuid(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value);
    }

    abstract protected function getUniqueFields(): array;
}
