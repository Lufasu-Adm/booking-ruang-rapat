<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Class VerifyCsrfToken
 * @package App\Http\Middleware
 * * Middleware ini bertanggung jawab untuk memverifikasi token CSRF (Cross-Site Request Forgery)
 * pada setiap request POST, PUT, PATCH, dan DELETE.
 * Ini membantu melindungi aplikasi dari serangan CSRF.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * URI yang harus dikecualikan dari verifikasi CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Tambahkan endpoint yang ingin dikecualikan dari CSRF di sini jika perlu
    ];
}