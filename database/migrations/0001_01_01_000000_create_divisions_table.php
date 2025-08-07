<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDivisionsTable
 * @package Database\Migrations
 *
 * Migrasi untuk membuat tabel 'divisions'.
 * Tabel ini digunakan untuk menyimpan data divisi.
 */
return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel 'divisions'.
     */
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (hapus tabel) jika diperlukan.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};