<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlatformSetPermissionsTeamId
{
    public function handle(Request $request, Closure $next): Response
    {
        setPermissionsTeamId(99999);

        return $next($request);
    }
}
