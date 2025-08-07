<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Class BookingController
 * @package App\Http\Controllers
 *
 * Controller untuk mengelola semua fungsionalitas terkait booking ruangan.
 * Meliputi pembuatan, persetujuan, penolakan, hingga rekapitulasi.
 */
class BookingController extends BaseController
{
    /**
     * BookingController constructor.
     * Menerapkan middleware 'auth' untuk semua metode di controller ini.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan riwayat booking milik pengguna yang sedang login.
     *
     * @return \Illuminate\Contracts\View\View
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
     * Menampilkan form untuk membuat booking baru.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $divisions = Division::all();
        $rooms = [];
        return view('bookings.create', compact('rooms', 'divisions'));
    }

    /**
     * Menyimpan data booking baru ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'     => 'required|exists:rooms,id',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'department'  => 'required|string|max:100',
            'purpose'     => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $room = Room::findOrFail($validated['room_id']);

        if (! $room->is_available) {
            return back()->withErrors(['room_id' => 'Ruangan tidak tersedia.'])->withInput();
        }

        $newStart = $validated['date'].' '.$validated['start_time'].':00';
        $newEnd = $validated['date'].' '.$validated['end_time'].':00';

        $exists = Booking::where('room_id', $room->id)
            ->where('date', $validated['date'])
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->where('start_time', '<', $newEnd)
                      ->where('end_time', '>', $newStart);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['start_time' => 'Jadwal yang dipilih tumpang tindih dengan booking lain.'])->withInput();
        }

        $pic = User::where('role', 'admin')
            ->where('division_id', $room->division_id)
            ->first();

        if (! $pic) {
            return back()->withErrors(['room_id' => 'PIC untuk divisi ini tidak ditemukan.'])->withInput();
        }

        Booking::create([
            'user_id'     => $user->id,
            'room_id'     => $room->id,
            'date'        => $validated['date'],
            'start_time'  => $newStart,
            'end_time'    => $newEnd,
            'division_id' => $room->division_id,
            'department'  => $validated['department'],
            'purpose'     => $validated['purpose'],
            'status'      => 'pending',
            'pic_user_id' => $pic->id,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat.');
    }

    /**
     * Menampilkan daftar booking dengan status 'pending' untuk admin divisi.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function all()
    {
        $user = auth()->user();
        $bookings = Booking::with(['room', 'user', 'pic'])
            ->where('status', 'pending')
            ->where('division_id', $user->division_id)
            ->orderByDesc('date')
            ->get();
        return view('admin.bookings', compact('bookings'));
    }

    /**
     * Menyetujui sebuah booking.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $user = auth()->user();
        $booking = Booking::findOrFail($id);
        if (strtolower($user->role) !== 'admin' || $user->division_id !== $booking->division_id) {
            abort(403);
        }
        $booking->update(['status' => 'approved']);
        return back()->with('success', 'Booking disetujui.');
    }

    /**
     * Menolak sebuah booking.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($id)
    {
        $user = auth()->user();
        $booking = Booking::findOrFail($id);
        if (strtolower($user->role) !== 'admin' || $user->division_id !== $booking->division_id) {
            abort(403);
        }
        $booking->update(['status' => 'rejected']);
        return back()->with('success', 'Booking ditolak.');
    }

    /**
     * Menampilkan daftar peserta yang hadir untuk sebuah booking.
     *
     * @param Booking $booking
     * @return \Illuminate\Contracts\View\View
     */
    public function showAttendees(Booking $booking)
    {
        $user = auth()->user();

        $isSuperAdmin = strtolower($user->role) === 'super_admin';
        $isAdmin = strtolower($user->role) === 'admin';
        $isTheBooker = $user->id === $booking->user_id;

        if (! ($isSuperAdmin || $isAdmin || $isTheBooker)) {
            abort(403, 'ACCESS DENIED');
        }

        $attendees = $booking->attendances()->get();

        return view('attendance.attendees', compact('booking', 'attendees'));
    }

    /**
     * Menampilkan form filter dan preview rekap booking.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function showExportFilter(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $bookings = [];
        $user = auth()->user();

        if ($startDate && $endDate) {
            $query = Booking::with(['room.division', 'user']);

            if (strtolower($user->role) === 'admin') {
                $query->where('division_id', $user->division_id);
            }

            $bookings = $query->whereDate('date', '>=', $startDate)
                            ->whereDate('date', '<=', $endDate)
                            ->orderBy('date', 'asc')
                            ->get();
        }

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'bookings' => $bookings,
        ];

        if (strtolower($user->role) === 'super_admin') {
            return view('bookings.superadmin_rekap_filter', $data);
        }

        return view('bookings.rekap_filter', $data);
    }

    /**
     * Mengekspor rekap booking dalam rentang tanggal tertentu ke format PDF.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $start = $request->start_date;
        $end = $request->end_date;
        $user = auth()->user();

        $query = Booking::with(['room.division', 'user']);
        $title = 'Rekap Booking Ruangan';

        if (strtolower($user->role) === 'admin') {
            $query->where('division_id', $user->division_id);
            $division = Division::find($user->division_id);
            $title = 'Rekap Booking Divisi ' . ($division ? $division->name : '');
        } elseif (strtolower($user->role) === 'super_admin') {
            $title = 'Rekap Booking Semua Divisi';
        }

        $bookings = $query->whereDate('date', '>=', $start)
                        ->whereDate('date', '<=', $end)
                        ->orderBy('date', 'asc')
                        ->get();

        $groupedBookings = $bookings->groupBy('room.division.name');

        $pdf = PDF::loadView('bookings.rekap_pdf', [
            'title' => $title,
            'groupedBookings' => $groupedBookings,
            'startDate' => $start,
            'endDate' => $end,
            'printedBy' => $user->name,
            'printDate' => Carbon::now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('RekapBooking_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Mengekspor semua rekap booking ke format PDF tanpa filter tanggal.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function rekapSemua()
    {
        $user = auth()->user();
        $query = Booking::with(['room.division', 'user']);
        $title = 'Rekap Booking Ruangan';

        if (strtolower($user->role) === 'admin') {
            $query->where('division_id', $user->division_id);
            $division = Division::find($user->division_id);
            $title = 'Rekap Booking Divisi ' . ($division ? $division->name : '');
        } elseif (strtolower($user->role) === 'super_admin') {
            $title = 'Rekap Booking Semua Divisi';
        }

        $bookings = $query->orderBy('date', 'asc')->get();

        $groupedBookings = $bookings->groupBy('room.division.name');

        $pdf = PDF::loadView('bookings.rekap_pdf', [
            'title' => $title,
            'groupedBookings' => $groupedBookings,
            'startDate' => null,
            'endDate' => null,
            'printedBy' => $user->name,
            'printDate' => Carbon::now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('RekapBooking_Semua_' . now()->format('Ymd_His') . '.pdf');
    }
}