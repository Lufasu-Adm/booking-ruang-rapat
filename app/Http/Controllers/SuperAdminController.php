<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function index()
    {
        $divisions = Division::with('users')->get();
        return view('superadmin.dashboard', compact('divisions'));
    }

    public function create()
    {
        return view('superadmin.divisions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:divisions,name',
            'admin_name'   => 'required|string|max:100',
            'admin_email'  => 'required|email|unique:users,email',
            'user_name'    => 'required|string|max:100',
            'user_email'   => 'required|email|unique:users,email',
        ]);

        // Buat Divisi
        $division = Division::create([
            'name' => $validated['name'],
        ]);

        // Buat Admin
        User::create([
            'name'         => $validated['admin_name'],
            'email'        => $validated['admin_email'],
            'role'         => 'admin',
            'division_id'  => $division->id,
            'password'     => bcrypt('password'),
        ]);

        // Buat User
        User::create([
            'name'         => $validated['user_name'],
            'email'        => $validated['user_email'],
            'role'         => 'user',
            'division_id'  => $division->id,
            'password'     => bcrypt('password'),
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $division = Division::with('users')->findOrFail($id);
        $admin = $division->users->where('role', 'admin')->first();
        $user  = $division->users->where('role', 'user')->first();

        return view('superadmin.divisions.edit', compact('division', 'admin', 'user'));
    }

    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:divisions,name,' . $division->id,
            'admin_name'   => 'required|string|max:100',
            'admin_email'  => 'required|email',
            'user_name'    => 'required|string|max:100',
            'user_email'   => 'required|email',
        ]);

        // Update Division
        $division->update([
            'name' => $validated['name'],
        ]);

        // Update Admin
        $admin = User::where('division_id', $division->id)->where('role', 'admin')->first();
        if ($admin) {
            $admin->update([
                'name'  => $validated['admin_name'],
                'email' => $validated['admin_email'],
            ]);
        }

        // Update User
        $user = User::where('division_id', $division->id)->where('role', 'user')->first();
        if ($user) {
            $user->update([
                'name'  => $validated['user_name'],
                'email' => $validated['user_email'],
            ]);
        }

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $division = Division::findOrFail($id);

            // Hapus semua user terkait
            User::where('division_id', $division->id)->delete();

            // Hapus divisinya
            $division->delete();
        });

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil dihapus.');
    }
}
