<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class DummyBookingSeeder
 * @package Database\Seeders
 *
 * Seeder untuk mengisi tabel 'bookings' dengan data dummy.
 * Catatan: Kode di dalamnya saat ini dikomentari (commented out).
 */
class DummyBookingSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     * Saat ini tidak menjalankan logika apa pun karena kode dikomentari.
     */
    public function run(): void
    {
        // Logika untuk membuat booking dummy
        // $users = DB::table('users')->where('role', 'user')->get();
        // $admins = DB::table('users')->where('role', 'admin')->get()->keyBy('division_id');
        // $rooms = DB::table('rooms')->get();
        // $statuses = ['pending', 'approved', 'rejected'];
        // $bookings = [];
        // foreach ($users as $i => $user) {
        //     // ... logika ...
        // }
        // DB::table('bookings')->insertOrIgnore($bookings);
    }
}