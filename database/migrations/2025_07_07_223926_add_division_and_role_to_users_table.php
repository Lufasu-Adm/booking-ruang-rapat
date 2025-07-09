<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu apakah kolom belum ada
            if (!Schema::hasColumn('users', 'division')) {
                $table->string('division')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('division');
            }

            // Tambahkan unique hanya jika kolom division dan role ada
            if (Schema::hasColumn('users', 'division') && Schema::hasColumn('users', 'role')) {
                $table->unique(['division', 'role'], 'users_division_role_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_division_role_unique');

            // Hindari drop kolom kalau belum ada
            if (Schema::hasColumn('users', 'division')) {
                $table->dropColumn('division');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};