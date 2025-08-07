<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddDivisionAndRoleToUsersTable
 * @package Database\Migrations
 *
 * Migrasi untuk menambahkan kolom 'division' dan 'role' ke tabel 'users'.
 * Juga menambahkan constraint unik untuk kombinasi 'division' dan 'role'.
 */
return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'division')) {
                $table->string('division')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'user'])->default('user')->after('division');
            }

            if (Schema::hasColumn('users', 'division') && Schema::hasColumn('users', 'role')) {
                $table->unique(['division', 'role'], 'users_division_role_unique');
            }
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_division_role_unique');

            if (Schema::hasColumn('users', 'division')) {
                $table->dropColumn('division');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};