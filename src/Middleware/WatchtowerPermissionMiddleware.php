<?php

namespace Aguaralabs\Watchtower\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class WatchtowerPermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        if (auth()->guest() || !Gate::allows($permission)) {
            abort(403, 'No tienes los permisos necesarios para acceder a esta sección.');
        }

        return $next($request);
    }
}