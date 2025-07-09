<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->integer('capacity');
        $table->unsignedBigInteger('division_id')->nullable(); // tambahkan ini kalau belum
        $table->string('location')->nullable(); // optional
        $table->boolean('is_available')->default(true); // optional
        $table->timestamps();

    $table->unique(['name', 'division_id']); // kombinasi unik, bukan name saja
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
