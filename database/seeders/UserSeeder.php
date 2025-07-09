<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Tambahkan Super Admin jika belum ada
        if (!DB::table('users')->where('email', 'superadmin@example.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'), // default password
                'role' => 'super_admin',
                'division_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ✅ Tambahkan user dan admin per divisi
        $divisions = DB::table('divisions')->get();

        foreach ($divisions as $division) {
            $userEmail = 'user_' . Str::slug($division->name, '_') . '@example.com';
            $adminEmail = 'admin_' . Str::slug($division->name, '_') . '@example.com';

            if (!DB::table('users')->where('email', $userEmail)->exists()) {
                DB::table('users')->insert([
                    'name' => 'User ' . $division->name,
                    'email' => $userEmail,
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'division_id' => $division->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (!DB::table('users')->where('email', $adminEmail)->exists()) {
                DB::table('users')->insert([
                    'name' => 'Admin ' . $division->name,
                    'email' => $adminEmail,
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'division_id' => $division->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}