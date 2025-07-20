<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login dan berperan sebagai super_admin
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403, 'Akses ditolak. Hanya super admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}