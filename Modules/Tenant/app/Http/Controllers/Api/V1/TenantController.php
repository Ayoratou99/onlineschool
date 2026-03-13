<?php

namespace Modules\Tenant\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Modules\Tenant\Http\Requests\IndexTenantRequest;
use Modules\Tenant\Http\Requests\ShowTenantRequest;
use Modules\Tenant\Http\Requests\StatsTenantRequest;
use Modules\Tenant\Http\Requests\StoreTenantRequest;
use Modules\Tenant\Http\Requests\UpdateTenantRequest;
use Modules\Tenant\Models\Tenant;
use Modules\Tenant\Services\TenantStatsService;

class TenantController extends Controller
{
    public function __construct(
        protected TenantStatsService $statsService
    ) {
        parent::__construct(null);
    }

    public function index(IndexTenantRequest $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $withStats = $request->boolean('with_stats');
        $tenants = Tenant::with('domains')->orderBy('created_at', 'desc')->paginate($perPage);

        if ($withStats) {
            $stats = $this->statsService->getStatsForAllTenants();
            foreach ($tenants->items() as $tenant) {
                $tenant->stats = $stats[$tenant->id] ?? ['users_count' => 0, 'database_size_bytes' => 0, 'database_size_mb' => 0];
            }
        }

        return $this->sendResponse($tenants, 'FUIP_100');
    }

    public function store(StoreTenantRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $id = $validated['id'];
        $data = $validated['data'] ?? [];
        $domains = $validated['domains'];

        $tenant = Tenant::create(array_merge(['id' => $id], $data));

        foreach ($domains as $domain) {
            $tenant->domains()->create(['domain' => $domain]);
        }

        Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
        ]);

        $tenant->load('domains');
        return $this->sendResponse($tenant, 'FUIP_201', 201);
    }

    public function show(ShowTenantRequest $request, Tenant $tenant): JsonResponse
    {
        $tenant->load('domains');
        if ($request->boolean('with_stats')) {
            $tenant->stats = $this->statsService->getStatsForTenant($tenant);
        }
        return $this->sendResponse($tenant, 'FUIP_101');
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): JsonResponse
    {
        $validated = $request->validated();

        if (array_key_exists('data', $validated)) {
            foreach ($validated['data'] as $key => $value) {
                $tenant->{$key} = $value;
            }
            $tenant->save();
        }

        if (isset($validated['domains'])) {
            $tenant->domains()->delete();
            foreach ($validated['domains'] as $domain) {
                $tenant->domains()->create(['domain' => $domain]);
            }
        }

        $tenant->load('domains');
        return $this->sendResponse($tenant, 'FUIP_200');
    }

    public function destroy(Tenant $tenant): JsonResponse
    {
        $this->authorize('delete', $tenant);
        $tenant->delete();
        return $this->sendResponse(null, 'FUIP_204');
    }

    public function clean(Tenant $tenant): JsonResponse
    {
        $this->authorize('clean', $tenant);

        Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id],
        ]);

        $tenant->load('domains');
        return $this->sendResponse($tenant, 'FUIP_200');
    }

    public function lock(Tenant $tenant): JsonResponse
    {
        $this->authorize('update', $tenant);
        $tenant->locked = true;
        $tenant->save();
        $tenant->load('domains');
        return $this->sendResponse($tenant, 'FUIP_200');
    }

    public function unlock(Tenant $tenant): JsonResponse
    {
        $this->authorize('update', $tenant);
        $tenant->locked = false;
        $tenant->save();
        $tenant->load('domains');
        return $this->sendResponse($tenant, 'FUIP_200');
    }

    /**
     * Dashboard stats: totals and per-tenant stats for charts.
     */
    public function stats(StatsTenantRequest $request): JsonResponse
    {
        $allStats = $this->statsService->getStatsForAllTenants();
        $totalUsers = 0;
        $totalBytes = 0;
        $byTenant = [];
        foreach (Tenant::with('domains')->orderBy('created_at', 'desc')->get() as $tenant) {
            $s = $allStats[$tenant->id] ?? ['users_count' => 0, 'database_size_bytes' => 0, 'database_size_mb' => 0];
            $totalUsers += $s['users_count'];
            $totalBytes += $s['database_size_bytes'];
            $byTenant[] = [
                'id' => $tenant->id,
                'locked' => $tenant->locked,
                'users_count' => $s['users_count'],
                'database_size_mb' => $s['database_size_mb'],
            ];
        }
        return $this->sendResponse([
            'total_tenants' => count($byTenant),
            'total_users' => $totalUsers,
            'total_database_size_mb' => round($totalBytes / (1024 * 1024), 2),
            'by_tenant' => $byTenant,
        ], 'FUIP_100');
    }
}
