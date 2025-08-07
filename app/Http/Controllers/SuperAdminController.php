<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller; // Tambahkan ini jika belum ada

/**
 * Class SuperAdminController
 * @package App\Http\Controllers
 *
 * Controller yang mengelola fungsionalitas khusus untuk Super Admin.
 * Termasuk manajemen divisi, user, dan password admin.
 */
class SuperAdminController extends Controller
{
    /**
     * Menampilkan dashboard utama Super Admin dengan daftar divisi yang terpaginasi.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $divisions = Division::with('users')->paginate(5);
        return view('superadmin.dashboard', compact('divisions'));
    }

    /**
     * Menampilkan form untuk menambah divisi baru.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('superadmin.divisions.create');
    }

    /**
     * Menyimpan divisi baru beserta user admin default.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:divisions,name',
            'admin_name'   => 'required|string|max:100',
            'admin_email'  => 'required|email|unique:users,email',
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
     *
     * @param int $id ID dari divisi.
     * @return \Illuminate\Contracts\View\View
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
     *
     * @param Request $request
     * @param int $id ID dari divisi.
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Menghapus divisi beserta semua user di dalamnya.
     *
     * @param int $id ID dari divisi.
     * @return \Illuminate\Http\RedirectResponse
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
     *
     * @param int $id ID dari user admin.
     * @return \Illuminate\Contracts\View\View
     */
    public function editPassword($id)
    {
        $admin = User::where('id', $id)->where('role', 'admin')->firstOrFail();
        return view('superadmin.admins.edit-password', compact('admin'));
    }

    /**
     * Memproses perubahan password untuk user admin.
     *
     * @param Request $request
     * @param int $id ID dari user admin.
     * @return \Illuminate\Http\RedirectResponse
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