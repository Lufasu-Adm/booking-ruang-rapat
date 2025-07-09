<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyRoomSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = DB::table('divisions')->get();

        $roomTemplates = [
            ['name' => 'KKB Workshop 1', 'capacity' => 35],
            ['name' => 'Welding Workshop 2', 'capacity' => 48],
            ['name' => 'Welding Workshop 3', 'capacity' => 20],
            ['name' => 'Binalaka Room 2', 'capacity' => 40],
            ['name' => 'Binalaka Room 4', 'capacity' => 20],
            ['name' => 'Binalaka Room 6', 'capacity' => 20],
            ['name' => 'Class Room 1', 'capacity' => 25],
            ['name' => 'Class Room 2', 'capacity' => 30],
            ['name' => 'Lounge Room 1', 'capacity' => 200],
            ['name' => 'Lounge Room 2', 'capacity' => 200],
            ['name' => 'ESQ Room', 'capacity' => 300],
            ['name' => 'Infrastructure Room', 'capacity' => 10],
            ['name' => 'Dinning Room', 'capacity' => 100],
            ['name' => 'Large Meeting Room', 'capacity' => 20],
            ['name' => 'Small Meeting Room', 'capacity' => 10],
        ];

        $allRooms = [];
        $now = now();

        foreach ($divisions as $division) {
            $floor = 1; // Reset floor for each division
            foreach ($roomTemplates as $room) {
                $allRooms[] = [
                    'name' => $room['name'] . ' - ' . $division->name, 
                    'description' => $division->name,
                    'capacity' => $room['capacity'],
                    'location' => 'Gedung A Lantai ' . $floor,
                    'division_id' => $division->id,
                    'is_available' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $floor = $floor < 5 ? $floor + 1 : 1;
            }
        }

        DB::table('rooms')->insertOrIgnore($allRooms);
    }
}