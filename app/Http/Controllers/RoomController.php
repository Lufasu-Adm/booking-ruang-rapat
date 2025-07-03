<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    // USER & ADMIN: List semua ruangan
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    // ADMIN: Form tambah ruangan
    public function create()
    {
        // Optional: hanya admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin!');
        }
        return view('rooms.create');
    }

    // ADMIN: Simpan ruangan baru
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin!');
        }
        $validated = $request->validate([
            'name' => 'required|unique:rooms',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
        ]);
        Room::create($validated);
        return redirect('/rooms')->with('success', 'Ruangan berhasil ditambah');
    }

    // ADMIN: Form edit ruangan
    public function edit(Room $room)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin!');
        }
        return view('rooms.edit', compact('room'));
    }

    // ADMIN: Update ruangan
    public function update(Request $request, Room $room)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin!');
        }
        $validated = $request->validate([
            'name' => 'required|unique:rooms,name,' . $room->id,
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
        ]);
        $room->update($validated);
        return redirect('/rooms')->with('success', 'Ruangan berhasil diupdate');
    }

    // ADMIN: Hapus ruangan
    public function destroy(Room $room)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin!');
        }
        $room->delete();
        return redirect('/rooms')->with('success', 'Ruangan berhasil dihapus');
    }
}
