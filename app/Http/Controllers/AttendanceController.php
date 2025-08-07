<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Tambahkan ini jika belum ada

/**
 * Class AttendanceController
 * @package App\Http\Controllers
 *
 * Controller yang mengelola fungsionalitas daftar hadir (attendance).
 */
class AttendanceController extends Controller
{
    /**
     * Menampilkan form daftar hadir untuk sebuah booking.
     * Form hanya bisa diakses jika booking sudah disetujui ('approved').
     *
     * @param Booking $booking
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Booking $booking)
    {
        if ($booking->status !== 'approved') {
            abort(404, 'Attendance list is not available.');
        }
        return view('attendance.create', compact('booking'));
    }

    /**
     * Menyimpan data peserta yang hadir dari form.
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'nip'      => 'nullable|string|max:50',
            'name'     => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'agency'   => 'required|string|max:100',
        ]);

        $booking->attendances()->create($validated);

        return redirect()->route('attendance.create', $booking)->with('success', 'Terima kasih, kehadiran Anda telah dicatat!');
    }
}