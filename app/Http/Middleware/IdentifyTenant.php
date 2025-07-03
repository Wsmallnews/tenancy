<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{

    public function handle(Request $request, Closure $next): Response
    {
        
        $panel = Filament::getPanel('admin');

        if (! $panel->hasTenancy()) {
            return $next($request);
        }

        if (! $request->route()->hasParameter('tenant')) {
            return $next($request);
        }

        $tenant = $panel->getTenant($request->route()->parameter('tenant'));

        $request->attributes->set('has_tenancy', true);
        $request->attributes->set('current_tenant', $tenant);

        return $next($request);
    }
}
