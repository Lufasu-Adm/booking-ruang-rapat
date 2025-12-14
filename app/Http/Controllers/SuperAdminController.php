<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

/**
 * Class SuperAdminController
 * @package App\Http\Controllers
 *
 * Controller yang mengelola fungsionalitas khusus untuk Super Admin.
 * Modifikasi: Kolom email sekarang menerima username biasa (string), bukan format email wajib.
 */
class SuperAdminController extends Controller
{
    /**
     * Menampilkan dashboard utama Super Admin dengan daftar divisi yang terpaginasi.
     */
    public function index()
    {
        $divisions = Division::with('users')->paginate(5);
        return view('superadmin.dashboard', compact('divisions'));
    }

    /**
     * Menampilkan form untuk menambah divisi baru.
     */
    public function create()
    {
        return view('superadmin.divisions.create');
    }

    /**
     * Menyimpan divisi baru beserta user admin default.
     */
    public function store(Request $request)
    {
        // MODIFIKASI: Validasi 'admin_email' diubah dari 'email' menjadi 'string'
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:divisions,name',
            'admin_name'   => 'required|string|max:100',
            'admin_email'  => 'required|string|unique:users,email', 
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
        });

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit divisi.
     */
    public function edit($id)
    {
        $division = Division::with('users')->findOrFail($id);
        $admin = $division->users->where('role', 'admin')->first();
        $user  = $division->users->where('role', 'user')->first();

        return view('superadmin.divisions.edit', compact('division', 'admin', 'user'));
    }

    /**
     * Memproses pembaruan data divisi dan admin.
     */
    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $admin = User::where('division_id', $division->id)->where('role', 'admin')->first();
        $user  = User::where('division_id', $division->id)->where('role', 'user')->first();

        // MODIFIKASI: Validasi 'admin_email' diubah jadi 'string'
        $validated = $request->validate([
            'name'                  => 'required|string|max:100|unique:divisions,name,' . $division->id,
            'admin_name'            => 'required|string|max:100',
            'admin_email'           => 'required|string|unique:users,email,' . ($admin->id ?? 'null'),
            'admin_password'        => 'nullable|string|min:6|confirmed',
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

            // Update user biasa jika ada
            if ($user && isset($validated['user_name'])) { 
                $user->update([
                    'name'  => $validated['user_name'],
                    'email' => $validated['user_email'],
                ]);
            }
        });

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Menghapus divisi beserta semua user di dalamnya.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $division = Division::findOrFail($id);
            User::where('division_id', $division->id)->delete();
            $division->delete();
        });

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil dihapus.');
    }

    /**
     * Menampilkan form untuk mengubah password admin.
     */
    public function editPassword($id)
    {
        $admin = User::where('id', $id)->where('role', 'admin')->firstOrFail();
        return view('superadmin.admins.edit-password', compact('admin'));
    }

    /**
     * Memproses perubahan password untuk user admin.
     */
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