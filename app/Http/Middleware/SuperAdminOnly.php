<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->role !== 'super_admin') {
            abort(403, 'Access denied. Only super admin can access this.');
        }

        return $next($request);
    }
}