<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        // Extract subdomain (e.g. acme.pulseops.app)
        $host = $request->getHost(); 
        $parts = explode('.', $host);

        // Assume domain is pulseops.app -> subdomain is first part
        if (count($parts) < 3) {
            throw new NotFoundHttpException('Tenant not specified.');
        }

        $subdomain = $parts[0];

        // Find tenant by subdomain
        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (!$tenant) {
            throw new NotFoundHttpException('Tenant does not exist.');
        }

        // Share tenant globally for the request
        app()->instance('currentTenant', $tenant);

        // Optionally bind to request
        $request->attributes->set('tenant', $tenant);

        // Add tenant to Auth or services if needed (optional)
        // auth()->setTenant($tenant);

        return $next($request);
    }
}
