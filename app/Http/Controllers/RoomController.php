<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    // USER & ADMIN: Lihat semua ruangan, bisa filter by divisi
    public function index(Request $request)
    {
        $divisions = Division::all();
        $selectedDivision = $request->input('division_id');

        $roomsQuery = Room::with('division');

        if ($selectedDivision) {
            $roomsQuery->where('division_id', $selectedDivision);
        }

        $rooms = $roomsQuery->get();

        return view('rooms.index', compact('rooms', 'divisions', 'selectedDivision'));
    }

    // ADMIN: Khusus lihat ruangan dari divisinya
    public function adminIndex()
    {
        $user = Auth::user();
        $rooms = Room::with('division')
            ->where('division_id', $user->division_id)
            ->get();

        return view('admin.rooms', compact('rooms'));
    }

    // SUPERADMIN & ADMIN: Form tambah ruangan
    public function create()
    {
        $user = Auth::user();

        $divisions = $user->role === 'admin'
            ? Division::all()
            : Division::where('id', $user->division_id)->get();

        return view('rooms.create', compact('divisions'));
    }

    // SUPERADMIN & ADMIN: Simpan ruangan
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
        abort(403, 'Hanya admin yang dapat menyimpan ruangan.');
    }

        $validated = $request->validate([
            'name' => 'required|unique:rooms',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'location' => 'nullable|string|max:255',
        ]);

        $validated['division_id'] = $user->division_id;

        // Tetapkan division_id berdasarkan role
        if ($user->role === 'admin') {
            $validated['division_id'] = $request->validate([
                'division_id' => 'required|exists:divisions,id',
            ])['division_id'];
        } else {
            $validated['division_id'] = $user->division_id;
        }

        Room::create($validated);

        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    // SUPERADMIN & ADMIN: Form edit
    public function edit(Room $room)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && $room->division_id !== $user->division_id) {
            abort(403, 'Anda tidak boleh mengedit ruangan dari divisi lain.');
        }

        $divisions = $user->role === 'admin'
            ? Division::all()
            : Division::where('id', $user->division_id)->get();

        return view('rooms.edit', compact('room', 'divisions'));
    }

    // SUPERADMIN & ADMIN: Update
    public function update(Request $request, Room $room)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && $room->division_id !== $user->division_id) {
            abort(403, 'Anda tidak boleh mengubah ruangan dari divisi lain.');
        }

        $validated = $request->validate([
            'name' => 'required|unique:rooms,name,' . $room->id,
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'location' => 'nullable|string|max:255',
        ]);

        if ($user->role === 'admin') {
            $validated['division_id'] = $request->validate([
                'division_id' => 'required|exists:divisions,id',
            ])['division_id'];
        } else {
            $validated['division_id'] = $user->division_id;
        }

        $room->update($validated);

        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil diperbarui.');
    }

    // SUPERADMIN & ADMIN: Hapus
    public function destroy(Room $room)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && $room->division_id !== $user->division_id) {
            abort(403, 'Anda tidak boleh menghapus ruangan dari divisi lain.');
        }

        $room->delete();

        return redirect()->route('admin.rooms')->with('success', 'Ruangan berhasil dihapus.');
    }

    // JSON API untuk ambil ruangan berdasarkan division (untuk AJAX)
    public function getByDivision($id)
    {
        $rooms = Room::where('division_id', $id)
                     ->where('is_available', true)
                     ->get();

        return response()->json($rooms);
    }

    // Digunakan jika ingin memfilter di view lain
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