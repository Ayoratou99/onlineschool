<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reject requests from central domains. Use on route groups that must only be
 * accessible on tenant domains (e.g. Securite, Document), not on central (127.0.0.1, localhost).
 */
class EnsureTenantDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $centralDomains = config('tenancy.central_domains', ['127.0.0.1', 'localhost']);
        if (in_array($request->getHost(), $centralDomains, true)) {
            abort(404);
        }

        return $next($request);
    }
}
