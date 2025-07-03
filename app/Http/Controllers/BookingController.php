<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controller as BaseController;

class BookingController extends BaseController
{
    // Pastikan hanya user login yang bisa akses
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil semua data booking dengan relasi user dan room
        $bookings = \App\Models\Booking::with(['user', 'room'])->latest()->get();

        // Kirim ke view bookings/index.blade.php
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        // Validasi form
        $validated = $request->validate([
            'room_id'    => 'required|exists:rooms,id',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
            // purpose opsional
        ]);

        // Gabungkan tanggal dan waktu
        $startDateTime = $validated['date'] . ' ' . $validated['start_time'] . ':00';
        $endDateTime = $validated['date'] . ' ' . $validated['end_time'] . ':00';

        // Ambil user yang sedang login
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Anda harus login untuk booking.');
        }

        // Simpan booking ke DB
        Booking::create([
            'user_id'    => $user->id,
            'room_id'    => $validated['room_id'],
            'date'       => $validated['date'],
            'start_time' => $startDateTime,
            'end_time'   => $endDateTime,
            'status'     => 'pending',
        ]);

        return redirect('/bookings')->with('success', 'Booking berhasil dibuat.');
    }

    // ADMIN: List semua booking
    public function all()
    {
        $bookings = Booking::with(['room', 'user'])
            ->orderByDesc('date')
            ->get();
        return view('admin.bookings', compact('bookings'));
    }

    // ADMIN: Approve booking
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'approved';
        $booking->save();
        return back()->with('success', 'Booking disetujui.');
    }

    // ADMIN: Reject booking
    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';
        $booking->save();
        return back()->with('success', 'Booking ditolak.');
    }
}
