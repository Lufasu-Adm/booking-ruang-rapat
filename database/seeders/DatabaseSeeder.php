<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 * @package Database\Seeders
 *
 * Seeder utama untuk menjalankan semua seeder lainnya secara berurutan.
 * Ini adalah titik masuk (entry point) utama untuk mengisi database.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Metode ini memanggil seeder lain untuk mengisi tabel divisions, users, rooms, dan bookings.
     */
    public function run(): void
    {
        // Panggil seeder lain di sini
        $this->call([
            DivisionSeeder::class,
            UserSeeder::class,
            DummyRoomSeeder::class,
            DummyBookingSeeder::class,
        ]);
    }
}