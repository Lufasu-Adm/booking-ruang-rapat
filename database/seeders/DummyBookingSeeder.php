<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dan room by email/nama (tidak hardcode id)
        $budi = \DB::table('users')->where('email', 'budi@user.com')->first();
        $siti = \DB::table('users')->where('email', 'siti@user.com')->first();
        $utama = \DB::table('rooms')->where('name', 'Ruang Rapat Utama')->first();
        $meeting1 = \DB::table('rooms')->where('name', 'Ruang Meeting 1')->first();

        if ($budi && $utama) {
            \DB::table('bookings')->insertOrIgnore([
                [
                    'user_id' => $budi->id,
                    'room_id' => $utama->id,
                    'date' => now()->format('Y-m-d'),
                    'start_time' => '09:00',
                    'end_time' => '10:00',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
        if ($siti && $meeting1) {
            \DB::table('bookings')->insertOrIgnore([
                [
                    'user_id' => $siti->id,
                    'room_id' => $meeting1->id,
                    'date' => now()->addDay()->format('Y-m-d'),
                    'start_time' => '14:00',
                    'end_time' => '15:00',
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
