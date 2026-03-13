<?php

declare(strict_types=1);

namespace Modules\Tenant\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Tenant\Models\Tenant;
use Modules\Tenant\Services\TenantBucketService;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class DeleteTenantBucketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected TenantWithDatabase $tenant
    ) {}

    public function handle(TenantBucketService $bucketService): void
    {
        if (! $this->tenant instanceof Tenant) {
            return;
        }

        try {
            $bucketService->deleteBucketForTenant($this->tenant);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
