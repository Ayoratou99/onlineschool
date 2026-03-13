<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Tenant\Models\Tenant;
use Modules\Tenant\Services\TenantBucketService;

/**
 * Store and resolve files in the current tenant's MinIO/S3 bucket.
 * In DB we store path as "tenant_bucket/..." (prefix + relative path).
 */
class TenantStorageService
{
    public const PATH_PREFIX = 'tenant_bucket';

    /**
     * Store a file in the tenant's bucket. Returns path to store in DB: "tenant_bucket/subpath/filename.ext"
     */
    public function put(string $pathUnderBucket, UploadedFile|string $contents, array $options = []): string
    {
        $path = self::PATH_PREFIX . '/' . ltrim($pathUnderBucket, '/');
        $disk = $this->tenantDisk();

        if ($contents instanceof UploadedFile) {
            $disk->putFileAs(dirname($path), $contents, basename($path), $options);
        } else {
            $disk->put($path, $contents, $options);
        }

        return $path;
    }

    /**
     * Whether the given path is a tenant_bucket path.
     */
    public static function isTenantBucketPath(?string $path): bool
    {
        return $path !== null && str_starts_with($path, self::PATH_PREFIX . '/');
    }

    /**
     * Get a temporary (signed) URL for a path stored as "tenant_bucket/...".
     * Returns null if path is not tenant_bucket or disk is not S3.
     */
    public function temporaryUrl(string $path, \DateTimeInterface|int $expiration = 3600): ?string
    {
        if (! self::isTenantBucketPath($path)) {
            return null;
        }
        $disk = $this->tenantDisk();
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        if (! $disk->getAdapter() instanceof \League\Flysystem\AwsS3V3\AwsS3V3Adapter) {
            return null;
        }
        $expiresAt = is_int($expiration) ? now()->addSeconds($expiration) : $expiration;
        return $disk->temporaryUrl($path, $expiresAt);
    }

    /**
     * Get a public URL for the path (if bucket is public). Otherwise use temporaryUrl or a proxy route.
     */
    public function url(string $path): ?string
    {
        if (! self::isTenantBucketPath($path)) {
            return null;
        }
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = $this->tenantDisk();
        return method_exists($disk, 'url') ? $disk->url($path) : null;
    }

    /**
     * Check if a path exists in the tenant bucket.
     */
    public function exists(string $path): bool
    {
        if (! self::isTenantBucketPath($path)) {
            return false;
        }
        return $this->tenantDisk()->exists($path);
    }

    /**
     * Delete a file at path "tenant_bucket/...".
     */
    public function delete(string $path): bool
    {
        if (! self::isTenantBucketPath($path)) {
            return false;
        }
        return $this->tenantDisk()->delete($path);
    }

    /**
     * Build a path string to store in DB: "tenant_bucket/subpath/filename".
     */
    public static function path(string $subpath): string
    {
        return self::PATH_PREFIX . '/' . ltrim($subpath, '/');
    }

    /**
     * Get a disk instance configured for the current tenant's bucket.
     */
    public function tenantDisk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        $tenant = tenancy()->tenant;
        if (! $tenant instanceof Tenant) {
            throw new \RuntimeException('Tenant context is required for tenant storage.');
        }
        $bucket = $tenant->bucket ?? TenantBucketService::bucketNameForTenant($tenant);
        $baseConfig = config('filesystems.disks.minio', config('filesystems.disks.s3', []));
        $config = array_merge($baseConfig, ['bucket' => $bucket]);
        return Storage::build($config);
    }
}
