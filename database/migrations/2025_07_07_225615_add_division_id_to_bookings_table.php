<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('division_id')
                ->nullable()
                ->after('user_id')
                ->constrained('divisions')
                ->nullOnDelete();

            if (Schema::hasColumn('bookings', 'division')) {
                $table->dropColumn('division');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');

            // Optional: kembalikan kolom division lama
            // $table->string('division')->nullable()->after('user_id');
        });
    }
};