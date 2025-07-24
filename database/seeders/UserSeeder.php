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
        // Hapus user lama yang role-nya 'user'
        DB::table('users')->where('role', 'user')->delete();

        // Buat Super Admin jika belum ada
        if (!DB::table('users')->where('email', 'superadmin')->exists()) {
            DB::table('users')->insert([
                'name'        => 'Super Admin',
                'email'       => 'superadmin',       // cukup 'superadmin'
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
            $slug = Str::slug($division->name, '_'); // misal 'kapal_perang'
            $name = 'Admin Divisi ' . $division->name;

            if (!DB::table('users')->where('email', $slug)->exists()) {
                DB::table('users')->insert([
                    'name'        => $name,
                    'email'       => $slug,              // cukup slug, tanpa @example.com
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