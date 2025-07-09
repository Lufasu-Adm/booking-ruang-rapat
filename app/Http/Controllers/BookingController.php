<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class BookingController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * USER: Tampilkan riwayat booking milik user sendiri
     */
    public function index()
    {
        $user = auth()->user();

        $bookings = Booking::with(['user', 'room', 'pic'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * USER: Tampilkan form booking (hanya ruangan tersedia)
     */
    public function create()
    {
        // $rooms = Room::where('is_available', true)->get();
        // $divisions = \App\Models\Division::whereHas('rooms')->get();
        $divisions = \App\Models\Division::all();
        $rooms = []; 
        return view('bookings.create', compact('rooms', 'divisions'));
    }

    /**
     * USER: Simpan data booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'     => 'required|exists:rooms,id',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'department'  => 'required|string|max:100',
        ]);

        $user = auth()->user();
        $room = Room::findOrFail($validated['room_id']);

        if (!$room->is_available) {
            return redirect()->back()->withErrors([
                'room_id' => 'Ruangan tidak tersedia.'
            ])->withInput();
        }

        // ✅ Cari admin dari divisi si ruangan
        $adminOfRoom = User::where('role', 'admin')
            ->where('division_id', $room->division_id)
            ->first();

        if (!$adminOfRoom) {
            return redirect()->back()->withErrors([
                'room_id' => 'Admin untuk divisi ruangan ini tidak ditemukan.'
            ])->withInput();
        }

        Booking::create([
            'user_id'      => $user->id,
            'room_id'      => $room->id,
            'date'         => $validated['date'],
            'start_time'   => $validated['date'] . ' ' . $validated['start_time'] . ':00',
            'end_time'     => $validated['date'] . ' ' . $validated['end_time'] . ':00',
            // 'division_id'  => $validated['division_id'],
            'division_id'  => $user->division_id,
            'department'   => $validated['department'],
            'status'       => 'pending',
            'pic_user_id'  => $adminOfRoom->id, // ✅ admin dari divisi ruangan
        ]);

        return redirect('/bookings')->with('success', 'Booking berhasil dibuat.');
    }

    /**
     * ADMIN: Lihat semua booking dari divisinya
     */
    public function all()
    {
        $admin = auth()->user();

        $bookings = Booking::with(['room', 'user', 'pic'])
            ->where('pic_user_id', $admin->id) // ✅ booking yang perlu dia acc
            ->orderByDesc('date')
            ->get();

        return view('admin.bookings', compact('bookings'));
    }

    /**
     * ADMIN: Approve booking jika dia yang jadi PIC
     */
    public function approve($id)
    {
        $admin = auth()->user();
        $booking = Booking::findOrFail($id);

        if ($booking->pic_user_id !== $admin->id) {
            abort(403, 'Anda tidak berhak menyetujui booking ini.');
        }

        $booking->status = 'approved';
        $booking->save();

        return back()->with('success', 'Booking disetujui.');
    }

    /**
     * ADMIN: Reject booking jika dia yang jadi PIC
     */
    public function reject($id)
    {
        $admin = auth()->user();
        $booking = Booking::findOrFail($id);

        if ($booking->pic_user_id !== $admin->id) {
            abort(403, 'Anda tidak berhak menolak booking ini.');
        }

        $booking->status = 'rejected';
        $booking->save();

        return back()->with('success', 'Booking ditolak.');
    }
}