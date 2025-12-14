<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Room;
use App\Models\Division;
use App\Models\Booking;

class AttendanceLogicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Data Dasar
        $this->division = Division::create(['name' => 'General']);
        $this->room = Room::create(['name' => 'Aula', 'division_id' => $this->division->id, 'capacity' => 50]);
        $this->user = User::factory()->create(['division_id' => $this->division->id]);

        // Setup Booking Approved
        $this->booking = Booking::create([
            'user_id' => $this->user->id,
            'room_id' => $this->room->id,
            'division_id' => $this->division->id,
            'pic_user_id' => $this->user->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => now()->addDay()->format('Y-m-d') . ' 09:00:00',
            'end_time' => now()->addDay()->format('Y-m-d') . ' 11:00:00',
            'department' => 'Umum',
            'purpose' => 'Seminar',
            'status' => 'approved'
        ]);
    }

    /**
     * Skenario 1: Peserta berhasil isi daftar hadir (Happy Path)
     */
    public function test_peserta_bisa_isi_daftar_hadir_dengan_data_valid()
    {
        // PERBAIKAN: Menambahkan 'division' dan 'agency' sesuai Controller
        $dataPeserta = [
            'name' => 'Budi Santoso',
            'nip' => '12345678', // Nullable, tapi kita isi saja
            'division' => 'Keuangan', // Wajib (Required)
            'agency' => 'PT Mencari Cinta Sejati', // Wajib (Required)
        ];

        // Hit route attendance.store
        $response = $this->post(route('attendance.store', $this->booking->id), $dataPeserta);

        // Assert Redirect (Controller me-redirect kembali ke halaman create)
        $response->assertRedirect(route('attendance.create', $this->booking->id)); 
        
        // Assert Database
        $this->assertDatabaseHas('attendances', [
            'booking_id' => $this->booking->id,
            'name' => 'Budi Santoso',
            'division' => 'Keuangan',
            'agency' => 'PT Mencari Cinta Sejati'
        ]);
    }

    /**
     * Skenario 2: Validasi Gagal (Data Wajib Kosong)
     */
    public function test_gagal_isi_daftar_hadir_jika_data_tidak_lengkap()
    {
        $response = $this->post(route('attendance.store', $this->booking->id), [
            'name' => 'Budi',
            // division dan agency sengaja dikosongkan
        ]);

        // Harusnya error di field 'division' dan 'agency'
        $response->assertSessionHasErrors(['division', 'agency']);
    }

    /**
     * Skenario 3: Keamanan (Booking Tidak Ditemukan)
     */
    public function test_gagal_isi_daftar_hadir_untuk_booking_tidak_valid()
    {
        $idNgawur = 99999;
        
        $response = $this->post(route('attendance.store', $idNgawur), [
            'name' => 'Hacker',
            'division' => 'Test',
            'agency' => 'Test'
        ]);

        // Harusnya 404 Not Found
        $response->assertStatus(404);
    }

    /**
     * Skenario 4: Logic Status Booking
     * Form Absensi TIDAK BOLEH dibuka jika status booking masih 'pending' atau 'rejected'.
     */
    public function test_form_absensi_tidak_muncul_jika_booking_belum_approved()
    {
        // 1. Buat booking dengan status 'pending'
        $bookingPending = Booking::create([
            'user_id' => $this->user->id,
            'room_id' => $this->room->id,
            'division_id' => $this->division->id,
            'pic_user_id' => $this->user->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => now()->addDay()->format('Y-m-d') . ' 13:00:00',
            'end_time' => now()->addDay()->format('Y-m-d') . ' 15:00:00',
            'department' => 'Umum',
            'purpose' => 'Meeting Dadakan',
            'status' => 'pending' // Status PENDING
        ]);

        // 2. Coba akses halaman form absensi (Route: attendance.create)
        $response = $this->get(route('attendance.create', $bookingPending->id));

        // 3. Harusnya Error 404 (Sesuai logic controller)
        $response->assertStatus(404);
    }
}