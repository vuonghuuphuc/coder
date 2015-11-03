<?php

namespace Coder\Http\Middleware;

use Closure;

class PermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user()->hasPermission($role)) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                abort(404);
            }
        }

        return $next($request);
    }
}
