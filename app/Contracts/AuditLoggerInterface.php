<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AuditLoggerInterface
{
    public function logListed(string $entity): void;

    public function logViewed(Model $subject): void;
}
