<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Room;
use App\Models\Division;

class RoomLogicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Divisi
        $this->division = Division::create(['name' => 'IT Infrastructure']);
        
        // Setup Admin (User dengan role 'admin')
        $this->admin = User::factory()->create([
            'division_id' => $this->division->id,
            'role' => 'admin',
            'name' => 'Super Staff'
        ]);
    }

    /**
     * Skenario 1: Admin berhasil tambah ruangan baru (Happy Path)
     */
    public function test_admin_bisa_tambah_ruangan_baru()
    {
        $this->actingAs($this->admin);

        $roomData = [
            'name' => 'Lab Komputer A',
            'capacity' => 30,
            'division_id' => $this->division->id,
            'description' => 'Untuk praktikum',
            'is_available' => 1
        ];

        // Hit route rooms.store
        $response = $this->post(route('rooms.store'), $roomData);

        // Assert
        $response->assertRedirect(); // Biasanya redirect ke index ruangan
        $this->assertDatabaseHas('rooms', [
            'name' => 'Lab Komputer A',
            'capacity' => 30
        ]);
    }

    /**
     * Skenario 2: Update Status Ruangan (Maintenance Mode)
     * Ini kritikal: Admin mengubah status tersedia -> tidak tersedia.
     */
    public function test_admin_bisa_ubah_status_ruangan_jadi_maintenance()
    {
        // 1. Buat ruangan available
        $room = Room::create([
            'name' => 'Ruang Rapat Kecil',
            'division_id' => $this->division->id,
            'capacity' => 5,
            'is_available' => true
        ]);

        $this->actingAs($this->admin);

        // 2. Update data (ubah is_available jadi 0)
        $response = $this->put(route('rooms.update', $room->id), [
            'name' => 'Ruang Rapat Kecil',
            'division_id' => $this->division->id, // Kirim ulang data wajib
            'capacity' => 5,
            'description' => 'Sedang dicat ulang',
            'is_available' => 0 // Set False (Maintenance)
        ]);

        // 3. Cek Database
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'is_available' => 0 // Harusnya 0/false
        ]);
    }

    
}