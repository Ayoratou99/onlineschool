<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * When running in tenant context, block the request if the tenant is locked.
 * All API calls for that tenant will return 503 until the tenant is unlocked.
 */
class EnsureTenantNotLocked
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenancy()->tenant;
        if ($tenant && $tenant->locked) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant is locked. Access is temporarily disabled.',
                'code' => 'tenant_locked',
            ], 503);
        }

        return $next($request);
    }
}
