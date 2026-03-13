<?php

namespace Modules\ActivityLog\Services;

use App\Contracts\AuditLoggerInterface;
use Illuminate\Database\Eloquent\Model;
use Modules\ActivityLog\Enums\ActivityAction;

class AuditLogger implements AuditLoggerInterface
{
    public function __construct(protected ActivityLogger $logger) {}

    public function logListed(string $entity): void
    {
        $this->logger
            ->withTags(['read', 'list'])
            ->log(ActivityAction::LISTED, null, ['entity' => $entity]);
    }

    public function logViewed(Model $subject): void
    {
        $this->logger
            ->withTags(['read', 'view'])
            ->log(ActivityAction::VIEWED, $subject);
    }
}
