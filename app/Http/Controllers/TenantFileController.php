<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ShowTenantFileRequest;
use App\Services\TenantStorageService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Resolve stored paths "tenant_bucket/..." to a temporary URL (redirect) for the current tenant's MinIO bucket.
 */
class TenantFileController extends Controller
{
    public function __construct(protected TenantStorageService $storage)
    {
    }

    /**
     * GET /api/v1/parametrage/portail/file?path=tenant_bucket/...
     * Redirects to a temporary signed URL or returns 404.
     */
    public function show(ShowTenantFileRequest $request): RedirectResponse
    {
        $path = $request->query('path');
        if (! $this->storage->exists($path)) {
            throw new NotFoundHttpException('File not found.');
        }
        $url = $this->storage->temporaryUrl($path, 3600);
        if (! $url) {
            throw new NotFoundHttpException('URL could not be generated.');
        }
        return redirect($url);
    }
}
