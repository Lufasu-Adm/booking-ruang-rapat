<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SuperAdminOnly
 * @package App\Http\Middleware
 *
 * Middleware ini membatasi akses hanya untuk user yang memiliki role 'super_admin'.
 * Jika user tidak memiliki role ini, akses akan ditolak.
 */
class SuperAdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
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