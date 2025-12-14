<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Room;
use App\Models\Division;
use App\Models\Booking;
use Carbon\Carbon;

class BookingLogicTest extends TestCase
{
    // Membersihkan database setiap kali test dijalankan
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. Setup Divisi
        $this->division = Division::create(['name' => 'IT Support']);

        // 2. Setup User Biasa (Peminjam)
        $this->user = User::factory()->create([
            'division_id' => $this->division->id,
            'role' => 'user',
            'name' => 'Mahasiswa KP'
        ]);

        // 3. Setup Admin Divisi (PIC)
        $this->admin = User::factory()->create([
            'division_id' => $this->division->id,
            'role' => 'admin', 
            'name' => 'Admin IT'
        ]);

        // 4. Setup Ruangan
        $this->room = Room::create([
            'name' => 'Ruang Server',
            'division_id' => $this->division->id,
            'is_available' => true,
            'capacity' => 10,
            'description' => 'Test Room'
        ]);
    }

    /**
     * TEST 1: Happy Path
     * Memastikan booking berhasil dibuat jika semua data valid.
     */
    public function test_booking_berhasil_dibuat_jika_data_valid()
    {
        $this->actingAs($this->user);

        $bookingData = [
            'room_id' => $this->room->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'department' => 'Informatika',
            'purpose' => 'Rapat Skripsi',
        ];

        // Hit endpoint store
        $response = $this->post(route('booking.store'), $bookingData);

        // Assert redirect ke index
        $response->assertRedirect(route('bookings.index')); 
        
        // Assert data masuk ke database
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->user->id,
            'room_id' => $this->room->id,
            'status' => 'pending', 
            'pic_user_id' => $this->admin->id 
        ]);
    }

    /**
     * TEST 2: Logic Ketersediaan Ruangan
     * Memastikan booking ditolak jika status ruangan 'tidak tersedia'.
     */
    public function test_booking_gagal_jika_ruangan_tidak_tersedia()
    {
        // Set ruangan jadi maintenance
        $this->room->update(['is_available' => false]);

        $this->actingAs($this->user);

        $response = $this->post(route('booking.store'), [
            'room_id' => $this->room->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'department' => 'Test',
            'purpose' => 'Test',
        ]);

        // Assert error session
        $response->assertSessionHasErrors('room_id'); 
    }

    /**
     * TEST 3: Logic Anti-Bentrok (Overlap)
     * Memastikan booking ditolak jika jamnya bertabrakan dengan booking lain.
     */
    public function test_booking_gagal_jika_jadwal_bentrok()
    {
        $tanggal = Carbon::tomorrow()->format('Y-m-d');

        // Buat booking existing (Jam 10 - 12)
        Booking::create([
            'user_id' => $this->admin->id,
            'room_id' => $this->room->id,
            'division_id' => $this->division->id,
            'pic_user_id' => $this->admin->id,
            'date' => $tanggal,
            'start_time' => $tanggal . ' 10:00:00',
            'end_time' => $tanggal . ' 12:00:00',
            'department' => 'Existing',
            'purpose' => 'Existing',
            'status' => 'approved'
        ]);

        $this->actingAs($this->user);

        // Coba booking jam 11 - 13 (Tabrakan)
        $response = $this->post(route('booking.store'), [
            'room_id' => $this->room->id,
            'date' => $tanggal,
            'start_time' => '11:00', 
            'end_time' => '13:00',
            'department' => 'New',
            'purpose' => 'New',
        ]);

        $response->assertSessionHasErrors('start_time');
    }

    /**
     * TEST 4: Logic Validasi PIC
     * Memastikan booking gagal jika tidak ada Admin yang bertanggung jawab.
     */
    public function test_booking_gagal_jika_pic_admin_tidak_ditemukan()
    {
        $this->admin->delete(); // Hapus admin

        $this->actingAs($this->user);

        $response = $this->post(route('booking.store'), [
            'room_id' => $this->room->id, 
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'department' => 'Test',
            'purpose' => 'Test',
        ]);

        $response->assertSessionHasErrors(['room_id']);
    }

    /**
     * TEST 5: Logic Approval (Otorisasi Benar)
     * Admin dari divisi yang sama BOLEH meng-approve booking.
     */
    public function test_admin_bisa_approve_booking_satu_divisi()
    {
        // Buat Booking Pending
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'room_id' => $this->room->id,
            'division_id' => $this->division->id,
            'pic_user_id' => $this->admin->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => Carbon::tomorrow()->format('Y-m-d') . ' 10:00:00',
            'end_time' => Carbon::tomorrow()->format('Y-m-d') . ' 12:00:00',
            'department' => 'IT',
            'purpose' => 'Rapat',
            'status' => 'pending'
        ]);

        $this->actingAs($this->admin);

        // Approve
        $response = $this->patch(route('admin.bookings.approve', $booking->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'approved'
        ]);
    }

    /**
     * TEST 6: Logic Keamanan Approval (Otorisasi Salah)
     * Admin dari divisi LAIN tidak boleh meng-approve (Forbidden).
     */
    public function test_admin_beda_divisi_tidak_bisa_approve()
    {
        // Buat Booking Ruang IT
        $booking = Booking::create([
            'user_id' => $this->user->id,
            'room_id' => $this->room->id,
            'division_id' => $this->division->id,
            'pic_user_id' => $this->admin->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => Carbon::tomorrow()->format('Y-m-d') . ' 10:00:00',
            'end_time' => Carbon::tomorrow()->format('Y-m-d') . ' 12:00:00',
            'department' => 'IT',
            'purpose' => 'Rapat',
            'status' => 'pending'
        ]);

        // Buat Admin HRD (Divisi Beda)
        $divisionHR = Division::create(['name' => 'HRD']);
        $adminHR = User::factory()->create([
            'division_id' => $divisionHR->id,
            'role' => 'admin',
            'name' => 'Admin HRD'
        ]);

        $this->actingAs($adminHR);

        // Coba approve
        $response = $this->patch(route('admin.bookings.approve', $booking->id));

        // Harus 403 Forbidden
        $response->assertStatus(403);
        
        // Status tidak berubah
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'pending'
        ]);
    }

    /**
     * TEST 7: Logic Privasi (Pemilik Booking)
     * Pemilik booking BOLEH melihat daftar hadirnya sendiri.
     */
    public function test_pemilik_booking_bisa_lihat_daftar_hadir()
    {
        $booking = Booking::create([
            'user_id' => $this->user->id, // Milik user ini
            'room_id' => $this->room->id,
            'division_id' => $this->division->id,
            'pic_user_id' => $this->admin->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => Carbon::tomorrow()->format('Y-m-d') . ' 10:00:00',
            'end_time' => Carbon::tomorrow()->format('Y-m-d') . ' 12:00:00',
            'department' => 'IT',
            'purpose' => 'Rapat Internal',
            'status' => 'approved'
        ]);

        $this->actingAs($this->user);

        // Akses halaman attendees
        $response = $this->get(route('admin.bookings.attendees', $booking->id));

        // Boleh (200 OK)
        $response->assertStatus(200);
    }

    /**
     * TEST 8: Logic Keamanan Privasi (Orang Asing)
     * User lain (bukan admin/pemilik) TIDAK BOLEH mengintip daftar hadir.
     */
    public function test_user_lain_tidak_bisa_intip_daftar_hadir()
    {
        $booking = Booking::create([
            'user_id' => $this->user->id, // Milik User A
            'room_id' => $this->room->id,
            'division_id' => $this->division->id,
            'pic_user_id' => $this->admin->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => Carbon::tomorrow()->format('Y-m-d') . ' 10:00:00',
            'end_time' => Carbon::tomorrow()->format('Y-m-d') . ' 12:00:00',
            'department' => 'IT',
            'purpose' => 'Rahasia',
            'status' => 'approved'
        ]);

        // Buat User B (Iseng)
        $userIseng = User::factory()->create([
            'division_id' => $this->division->id,
            'role' => 'user',
            'name' => 'Si Pengintip'
        ]);

        $this->actingAs($userIseng);

        // Coba intip
        $response = $this->get(route('admin.bookings.attendees', $booking->id));

        // Ditolak (403 Forbidden)
        $response->assertStatus(403);
    }
}