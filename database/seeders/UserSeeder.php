<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class UserSeeder
 * @package Database\Seeders
 *
 * Seeder untuk mengisi tabel 'users' dengan user super admin dan user admin untuk setiap divisi.
 */
class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // Hapus user lama yang role-nya 'user'
        DB::table('users')->where('role', 'user')->delete();

        // Buat Super Admin jika belum ada
        if (!DB::table('users')->where('email', 'superadmin')->exists()) {
            DB::table('users')->insert([
                'name'        => 'Super Admin',
                'email'       => 'superadmin',
                'password'    => Hash::make('password'),
                'role'        => 'super_admin',
                'division_id' => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // Buat akun admin untuk setiap divisi
        $divisions = DB::table('divisions')->get();

        foreach ($divisions as $division) {
            $slug = Str::slug($division->name, '_');
            $name = 'Admin Divisi ' . $division->name;

            if (!DB::table('users')->where('email', $slug)->exists()) {
                DB::table('users')->insert([
                    'name'        => $name,
                    'email'       => $slug,
                    'password'    => Hash::make('password'),
                    'role'        => 'admin',
                    'division_id' => $division->id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}