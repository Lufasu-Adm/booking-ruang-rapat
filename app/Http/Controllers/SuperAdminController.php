<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;

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
            'password'     => bcrypt('password'), // default password
        ]);

        // Buat User
        User::create([
            'name'         => $validated['user_name'],
            'email'        => $validated['user_email'],
            'role'         => 'user',
            'division_id'  => $division->id,
            'password'     => bcrypt('password'), // default password
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'Divisi berhasil ditambahkan.');
    }
}