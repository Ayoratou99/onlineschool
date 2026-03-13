<?php

namespace App\Contracts;

interface LogsActivityContract
{
    public static function getActivityActions(): array;

    public static function getIgnoredActivityAttributes(): array;
}
