<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRoomsTable
 * @package Database\Migrations
 *
 * Migrasi untuk membuat tabel 'rooms' yang menyimpan data ruangan.
 */
return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
       Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('capacity');
            $table->unsignedBigInteger('division_id')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->unique(['name', 'division_id']);
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};