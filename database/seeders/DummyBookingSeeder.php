<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyBookingSeeder extends Seeder
{
    public function run(): void
    {
        // $users = DB::table('users')->where('role', 'user')->get();
        // $admins = DB::table('users')->where('role', 'admin')->get()->keyBy('division_id');
        // $rooms = DB::table('rooms')->get();

        // $statuses = ['pending', 'approved', 'rejected'];
        // $bookings = [];

        // foreach ($users as $i => $user) {
        //     // Ambil satu ruangan dari divisi berbeda
        //     $room = $rooms->firstWhere('division_id', '!=', $user->division_id);

        //     if ($room) {
        //         $bookings[] = [
        //             'user_id' => $user->id,
        //             'room_id' => $room->id,
        //             'division_id' => $user->division_id,
        //             'pic_user_id' => $admins[$room->division_id]->id ?? null,
        //             'date' => now()->addDays($i)->format('Y-m-d'),
        //             'start_time' => '10:00',
        //             'end_time' => '11:00',
        //             'status' => $statuses[array_rand($statuses)],
        //             'department' => 'Departemen A',
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //     }
        // }

        // DB::table('bookings')->insertOrIgnore($bookings);
    }
}
