<?php

return [
    'name' => 'Tenant',

    /*
    | Default central admin (used by AdminSeeder).
    | Override via env or do not run seeder in production.
    */
    'admin_default_email' => env('TENANT_ADMIN_EMAIL', 'admin@central.local'),
    'admin_default_name' => env('TENANT_ADMIN_NAME', 'Super Admin'),
    'admin_default_password' => env('TENANT_ADMIN_PASSWORD', 'password'),

    /*
    | MinIO/S3 bucket name prefix for per-tenant buckets (bucket name = {prefix}-{tenant_id}).
    */
    'minio_bucket_prefix' => env('MINIO_BUCKET_PREFIX', 'tenant'),
];
