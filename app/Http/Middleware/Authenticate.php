<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

/**
 * Class Authenticate
 * @package App\Http\Middleware
 * * Middleware ini digunakan untuk memastikan user sudah terotentikasi (login).
 * Jika user belum login, ia akan diarahkan ke halaman login.
 */
class Authenticate extends Middleware
{
    /**
     * Dapatkan path tempat user akan diarahkan jika mereka tidak terotentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            return route('login');
        }

        return null;
    }
}