<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insertOrIgnore([
            [
                'name' => 'Ruang Rapat Utama',
                'description' => 'Ruang utama kapasitas besar',
                'capacity' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Meeting 1',
                'description' => 'Ruang meeting lantai 1',
                'capacity' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Meeting 2',
                'description' => 'Ruang meeting lantai 2',
                'capacity' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
