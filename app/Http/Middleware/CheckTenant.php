<?php

namespace App\Http\Middleware;

use App\Enums\Teams\Status;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenant
{

    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentOrDefaultPanel();

        if (! $panel->hasTenancy()) {
            return $next($request);
        }

        $tenant = Filament::getTenant();

        if (!$tenant || $tenant->status == Status::Disabled) {
            abort(403, '当前租户已被禁用');
        }

        if ($tenant->status !== Status::Enable) {
            abort(403, '当前租户不可登录');
        }

        return $next($request);
    }
}
