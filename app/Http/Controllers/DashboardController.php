<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Hitung total ruangan
        $totalRooms = Room::count();

        // Hitung total booking (semua status)
        $totalBookings = Booking::count();

        // Hitung booking aktif (status 'approved' dan tanggal hari ini atau lebih)
        $activeBookings = Booking::where('status', 'approved')
                                 ->whereDate('date', '>=', Carbon::today())
                                 ->count();

        // Ambil 5 booking terbaru
        $recentBookings = Booking::with('room')
                                 ->orderBy('created_at', 'desc')
                                 ->take(5)
                                 ->get();

        // Kirim semua data ke view dashboard
        return view('dashboard', compact(
            'user',
            'totalRooms',
            'totalBookings',
            'activeBookings',
            'recentBookings'
        ));
    }
}