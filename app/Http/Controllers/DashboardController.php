<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Division;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard untuk superadmin (melihat seluruh divisi).
     */
    public function adminDashboard()
    {
        $divisions = Division::all();
        return view('admin.dashboard-admin', compact('divisions'));
    }

    /**
     * Tampilkan dashboard utama (baik untuk admin atau superadmin).
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            // Super admin melihat semua data
            $totalRooms     = Room::count();
            $totalBookings  = Booking::count();
            $activeBookings = Booking::where('status', 'approved')
                                     ->whereDate('date', '>=', Carbon::today())
                                     ->count();
            $recentBookings = Booking::with('room')
                                     ->orderByDesc('created_at')
                                     ->take(5)
                                     ->get();
        } else {
            // Admin hanya melihat data dari divisinya
            $totalRooms     = Room::where('division_id', $user->division_id)->count();
            $totalBookings  = Booking::where('division_id', $user->division_id)->count();
            $activeBookings = Booking::where('division_id', $user->division_id)
                                     ->where('status', 'approved')
                                     ->whereDate('date', '>=', Carbon::today())
                                     ->count();
            $recentBookings = Booking::with('room')
                                     ->where('division_id', $user->division_id)
                                     ->orderByDesc('created_at')
                                     ->take(5)
                                     ->get();
        }

        return view('dashboard', compact(
            'user',
            'totalRooms',
            'totalBookings',
            'activeBookings',
            'recentBookings'
        ));
    }
}