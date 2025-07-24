<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    // Semua: Lihat semua ruangan, bisa filter by divisi
    public function index(Request $request)
{
    $divisions = Division::all();
    $selectedDivision = $request->input('division_id');
    $perPage = 5; // Tentukan jumlah item per halaman

    $roomsQuery = Room::with('division');

    if ($selectedDivision) {
        $roomsQuery->where('division_id', $selectedDivision);
    }

    // Ganti ->get() menjadi ->paginate()
    // appends() digunakan agar filter divisi tidak hilang saat pindah halaman
    $rooms = $roomsQuery->paginate($perPage)->appends($request->query());

    return view('rooms.index', compact('rooms', 'divisions', 'selectedDivision'));
}

    // ADMIN: Lihat semua ruangan dari divisinya
public function adminIndex()
{
    $user = Auth::user();
    $perPage = 5; // Tentukan jumlah item per halaman

    if ($user->role === 'super_admin') {
        $rooms = Room::with('division')->paginate($perPage);
    } else {
        $rooms = Room::with('division')
            ->where('division_id', $user->division_id)
            ->paginate($perPage);
    }

    return view('admin.rooms', compact('rooms'));
}

    // ADMIN: Form tambah ruangan
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

    // ADMIN: Simpan ruangan
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

        // Cegah admin membuat ruangan untuk divisi lain
        if ($user->role !== 'super_admin' && $validated['division_id'] != $user->division_id) {
            abort(403, 'Anda tidak boleh menambah ruangan untuk divisi lain.');
        }

        Room::create($validated);

        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    // ADMIN: Form edit ruangan
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

    // ADMIN: Update ruangan
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

    // ADMIN: Hapus ruangan
    public function destroy(Room $room)
    {
        $user = Auth::user();

        if ($user->role !== 'super_admin' && $room->division_id !== $user->division_id) {
            abort(403, 'Anda tidak boleh menghapus ruangan dari divisi lain.');
        }

        $room->delete();

        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil dihapus.');
    }

    // JSON API untuk ambil ruangan berdasarkan division
    public function getByDivision($id)
    {
        $rooms = Room::where('division_id', $id)
                     ->where('is_available', true)
                     ->get();

        return response()->json($rooms);
    }

    // Form filter (digunakan di tempat lain jika perlu)
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