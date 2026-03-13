<?php

declare(strict_types=1);

namespace Modules\Tenant\Services;

use Aws\S3\S3Client;
use Modules\Tenant\Models\Tenant;

/**
 * Creates and deletes MinIO/S3 buckets per tenant.
 * Bucket name is derived from tenant id and stored on the tenant (data.bucket).
 */
class TenantBucketService
{
    private ?S3Client $client = null;

    public function __construct(
        protected string $disk = 'minio'
    ) {}

    /**
     * Build a valid S3/MinIO bucket name from tenant id (3–63 chars, lowercase, alphanumeric + hyphens).
     */
    public static function bucketNameForTenant(Tenant $tenant): string
    {
        $prefix = config('tenant.minio_bucket_prefix', 'tenant');
        $raw = $prefix . '-' . $tenant->id;
        $sanitized = strtolower((string) preg_replace('/[^a-z0-9-]/', '-', $raw));
        $sanitized = trim($sanitized, '-');
        $sanitized = preg_replace('/-+/', '-', $sanitized) ?? $sanitized;

        if (strlen($sanitized) < 3) {
            $sanitized = $prefix . '-' . substr(md5($tenant->id), 0, 8);
        }
        if (strlen($sanitized) > 63) {
            $sanitized = substr($sanitized, 0, 63);
        }

        return $sanitized;
    }

    /**
     * Create the bucket for the tenant and store the bucket name on the tenant.
     * No-op when MinIO/S3 is not configured (e.g. missing endpoint).
     */
    public function createBucketForTenant(Tenant $tenant): string
    {
        $bucketName = self::bucketNameForTenant($tenant);

        if (! $this->isConfigured()) {
            $tenant->bucket = $bucketName;
            $tenant->save();
            return $bucketName;
        }

        $client = $this->getClient();

        if (! $this->bucketExists($client, $bucketName)) {
            $client->createBucket(['Bucket' => $bucketName]);
        }

        $tenant->bucket = $bucketName;
        $tenant->save();

        return $bucketName;
    }

    /**
     * Delete the tenant's bucket if it exists (e.g. on tenant deletion).
     * Does not update the tenant record (tenant may already be deleted).
     * No-op when MinIO/S3 is not configured.
     */
    public function deleteBucketForTenant(Tenant $tenant): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        $bucketName = $tenant->bucket ?? self::bucketNameForTenant($tenant);
        $client = $this->getClient();

        if ($this->bucketExists($client, $bucketName)) {
            $this->emptyBucket($client, $bucketName);
            $client->deleteBucket(['Bucket' => $bucketName]);
        }
    }

    private function isConfigured(): bool
    {
        $config = config("filesystems.disks.{$this->disk}");
        return $config
            && ($config['driver'] ?? '') === 's3'
            && ! empty($config['endpoint'] ?? null)
            && ! empty($config['key'] ?? null)
            && ! empty($config['secret'] ?? null);
    }

    private function getClient(): S3Client
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $config = config("filesystems.disks.{$this->disk}");
        if (! $config || ($config['driver'] ?? '') !== 's3') {
            throw new \RuntimeException("Disk [{$this->disk}] is not configured for S3/MinIO.");
        }

        $this->client = new S3Client([
            'version' => 'latest',
            'region' => $config['region'] ?? 'us-east-1',
            'endpoint' => $config['endpoint'] ?? null,
            'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
            'credentials' => [
                'key' => $config['key'] ?? '',
                'secret' => $config['secret'] ?? '',
            ],
        ]);

        return $this->client;
    }

    private function bucketExists(S3Client $client, string $bucket): bool
    {
        try {
            $client->headBucket(['Bucket' => $bucket]);
            return true;
        } catch (\Aws\S3\Exception\S3Exception $e) {
            if ($e->getStatusCode() === 404) {
                return false;
            }
            throw $e;
        }
    }

    private function emptyBucket(S3Client $client, string $bucket): void
    {
        $paginator = $client->getPaginator('ListObjectsV2', ['Bucket' => $bucket]);
        foreach ($paginator as $result) {
            $objects = $result['Contents'] ?? [];
            if (count($objects) === 0) {
                continue;
            }
            $client->deleteObjects([
                'Bucket' => $bucket,
                'Delete' => [
                    'Objects' => array_map(fn ($o) => ['Key' => $o['Key']], $objects),
                ],
            ]);
        }
    }
}
