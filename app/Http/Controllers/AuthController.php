<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        // Hanya guest yang boleh akses login/register
        $this->middleware('guest')->only(['showLogin', 'showRegister']);
        // Hanya yang login boleh logout
        $this->middleware('auth')->only(['logout']);
    }

    /**
     * Tampilkan form register (khusus untuk admin)
     */
    public function showRegister()
    {
        return view('register');
    }

    /**
     * Proses registrasi akun admin
     */
    public function doRegister(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:6|confirmed',
            'division_id' => 'required|exists:divisions,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'admin';

        User::create($validated);

        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Proses login
     */
    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()->with('error', 'Email atau password salah');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Arahkan user berdasarkan role setelah login
     */
    public function redirectByRole()
    {
        return match (auth()->user()->role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            // Admin & user diarahkan ke route dashboard biasa
            'admin', 'user' => redirect()->route('dashboard'),
            default => abort(403, 'Role tidak dikenali.'),
        };
    }
}