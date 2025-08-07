<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * Class EncryptCookies
 * @package App\Http\Middleware
 * * Middleware ini mengenkripsi semua cookie yang dibuat oleh aplikasi.
 * Tujuannya adalah untuk memastikan data cookie tidak dapat dimanipulasi
 * oleh pengguna.
 */
class EncryptCookies extends Middleware
{
    /**
     * Nama-nama cookie yang tidak boleh dienkripsi.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Kosongkan atau sesuaikan jika ada cookie khusus
    ];
}