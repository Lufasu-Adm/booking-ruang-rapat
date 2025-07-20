<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // Dashboard utama Super Admin
    public function index()
    {
        $divisions = Division::with('users')->get();
        return view('superadmin.dashboard', compact('divisions'));
    }

    // Form tambah divisi
    public function create()
    {
        return view('superadmin.divisions.create');
    }

    // Simpan divisi baru beserta admin & user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:divisions,name',
            'admin_name'   => 'required|string|max:100',
            'admin_email'  => 'required|email|unique:users,email',
            'user_name'    => 'required|string|max:100',
            'user_email'   => 'required|email|unique:users,email',
        ]);

        DB::transaction(function () use ($validated) {
            $division = Division::create(['name' => $validated['name']]);

            User::create([
                'name'         => $validated['admin_name'],
                'email'        => $validated['admin_email'],
                'role'         => 'admin',
                'division_id'  => $division->id,
                'password'     => bcrypt('password'),
            ]);

            User::create([
                'name'         => $validated['user_name'],
                'email'        => $validated['user_email'],
                'role'         => 'user',
                'division_id'  => $division->id,
                'password'     => bcrypt('password'),
            ]);
        });

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil ditambahkan.');
    }

    // Form edit divisi
    public function edit($id)
    {
        $division = Division::with('users')->findOrFail($id);
        $admin = $division->users->where('role', 'admin')->first();
        $user  = $division->users->where('role', 'user')->first();

        return view('superadmin.divisions.edit', compact('division', 'admin', 'user'));
    }

    // Simpan update divisi
    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $admin = User::where('division_id', $division->id)->where('role', 'admin')->first();
        $user  = User::where('division_id', $division->id)->where('role', 'user')->first();

        $validated = $request->validate([
            'name'                  => 'required|string|max:100|unique:divisions,name,' . $division->id,
            'admin_name'            => 'required|string|max:100',
            'admin_email'           => 'required|email|unique:users,email,' . ($admin->id ?? 'null'),
            'admin_password'        => 'nullable|string|min:6|confirmed',
            'user_name'             => 'required|string|max:100',
            'user_email'            => 'required|email|unique:users,email,' . ($user->id ?? 'null'),
        ]);

        DB::transaction(function () use ($division, $admin, $user, $validated) {
            $division->update(['name' => $validated['name']]);

            if ($admin) {
                $admin->update([
                    'name'  => $validated['admin_name'],
                    'email' => $validated['admin_email'],
                ]);

                if (!empty($validated['admin_password'])) {
                    $admin->update([
                        'password' => Hash::make($validated['admin_password']),
                    ]);
                }
            }

            if ($user) {
                $user->update([
                    'name'  => $validated['user_name'],
                    'email' => $validated['user_email'],
                ]);
            }
        });

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil diperbarui.');
    }

    // Hapus divisi & user-nya
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $division = Division::findOrFail($id);
            User::where('division_id', $division->id)->delete();
            $division->delete();
        });

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil dihapus.');
    }

    // (Opsional) Form ubah password admin via route terpisah
    public function editPassword($id)
    {
        $admin = User::where('id', $id)->where('role', 'admin')->firstOrFail();
        return view('superadmin.admins.edit-password', compact('admin'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = User::where('id', $id)->where('role', 'admin')->firstOrFail();
        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'Password admin berhasil diubah.');
    }
}