<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RoomController
 * @package App\Http\Controllers
 *
 * Controller yang mengelola semua fungsionalitas terkait data ruangan.
 * Meliputi melihat, menambah, mengedit, dan menghapus ruangan.
 */
class RoomController extends Controller
{
    /**
     * Menampilkan daftar semua ruangan dengan opsi filter berdasarkan divisi.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $divisions = Division::all();
        $selectedDivision = $request->input('division_id');
        $perPage = 5;

        $roomsQuery = Room::with('division');

        if ($selectedDivision) {
            $roomsQuery->where('division_id', $selectedDivision);
        }

        $rooms = $roomsQuery->paginate($perPage)->appends($request->query());

        return view('rooms.index', compact('rooms', 'divisions', 'selectedDivision'));
    }

    /**
     * Menampilkan daftar ruangan untuk halaman admin.
     * Super admin dapat melihat semua ruangan, sedangkan admin hanya melihat ruangan di divisinya.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function adminIndex()
    {
        $user = Auth::user();
        $perPage = 5;

        if ($user->role === 'super_admin') {
            $rooms = Room::with('division')->paginate($perPage);
        } else {
            $rooms = Room::with('division')
                ->where('division_id', $user->division_id)
                ->paginate($perPage);
        }

        return view('admin.rooms', compact('rooms'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            $divisions = Division::all();
        } else {
            $divisions = Division::where('id', $user->division_id)->get();
        }

        return view('rooms.create', compact('divisions'));
    }

    /**
     * Menyimpan data ruangan baru.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => 'required|unique:rooms',
            'description'  => 'nullable|string',
            'capacity'     => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'location'     => 'nullable|string|max:255',
            'division_id'  => 'required|exists:divisions,id',
        ]);

        if ($user->role !== 'super_admin' && $validated['division_id'] != $user->division_id) {
            abort(403, 'Anda tidak boleh menambah ruangan untuk divisi lain.');
        }

        Room::create($validated);

        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit ruangan.
     *
     * @param Room $room
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Room $room)
    {
        $user = Auth::user();

        if ($user->role !== 'super_admin' && $room->division_id !== $user->division_id) {
            abort(403, 'Anda tidak boleh mengedit ruangan dari divisi lain.');
        }

        $divisions = ($user->role === 'super_admin')
            ? Division::all()
            : Division::where('id', $user->division_id)->get();

        return view('rooms.edit', compact('room', 'divisions'));
    }

    /**
     * Memproses pembaruan data ruangan.
     *
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Room $room)
    {
        $user = Auth::user();

        if ($user->role !== 'super_admin' && $room->division_id !== $user->division_id) {
            abort(403, 'Anda tidak boleh mengubah ruangan dari divisi lain.');
        }

        $validated = $request->validate([
            'name'         => 'required|unique:rooms,name,' . $room->id,
            'description'  => 'nullable|string',
            'capacity'     => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'location'     => 'nullable|string|max:255',
            'division_id'  => 'required|exists:divisions,id',
        ]);

        if ($user->role !== 'super_admin' && $validated['division_id'] != $user->division_id) {
            abort(403, 'Anda tidak boleh memindahkan ruangan ke divisi lain.');
        }

        $room->update($validated);

        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil diperbarui.');
    }

    /**
     * Menghapus ruangan dari database.
     *
     * @param Room $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Room $room)
    {
        $user = Auth::user();

        if ($user->role !== 'super_admin' && $room->division_id !== $user->division_id) {
            abort(403, 'Anda tidak boleh menghapus ruangan dari divisi lain.');
        }

        $room->delete();

        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil dihapus.');
    }

    /**
     * Mengembalikan daftar ruangan dalam format JSON berdasarkan ID divisi.
     *
     * @param int $id ID dari divisi.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByDivision($id)
    {
        $rooms = Room::where('division_id', $id)
                     ->where('is_available', true)
                     ->get();

        return response()->json($rooms);
    }

    /**
     * Menampilkan form filter ruangan berdasarkan divisi.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function filterByDivision(Request $request)
    {
        $divisions = Division::all();
        $selectedDivision = $request->input('division_id');
        $rooms = [];

        if ($selectedDivision) {
            $rooms = Room::where('division_id', $selectedDivision)->get();
        }

        return view('rooms.filter', compact('divisions', 'rooms', 'selectedDivision'));
    }
}