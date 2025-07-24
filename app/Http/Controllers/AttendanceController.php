<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Menampilkan form daftar hadir
    public function create(Booking $booking)
    {
        // Hanya tampilkan form jika booking sudah disetujui
        if ($booking->status !== 'approved') {
            abort(404, 'Attendance list is not available.');
        }
        return view('attendance.create', compact('booking'));
    }

    // Menyimpan data dari form
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