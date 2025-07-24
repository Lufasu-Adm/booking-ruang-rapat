<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct()
    {
        // Hanya guest (pengguna yang belum login) yang bisa mengakses halaman login dan register.
        $this->middleware('guest')->except('logout');
        // Hanya pengguna yang sudah login yang bisa logout.
        $this->middleware('auth')->only('logout');
    }

    /**
     * Menampilkan halaman form registrasi.
     */
    public function showRegister()
    {
        // Jika form register perlu daftar divisi, uncomment baris ini
        // $divisions = \App\Models\Division::all();
        // return view('register', compact('divisions'));
        return view('register');
    }

    /**
     * Memproses data dari form registrasi.
     */
    public function doRegister(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            // Menggunakan validasi email standar
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'    => ['required', 'confirmed', Password::defaults()],
            'division_id' => ['required', 'exists:divisions,id'],
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role']     = 'admin';

        User::create($validated);

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    /**
     * Menampilkan halaman form login.
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Memproses percobaan login dari pengguna.
     */
    public function doLogin(Request $request)
    {
        // Validasi disesuaikan dengan seeder Anda (email tidak harus format email)
        $credentials = $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Panggil fungsi redirect yang sudah kita buat
            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'Kombinasi email/username dan password salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Mengarahkan pengguna ke dashboard yang sesuai berdasarkan peran (role) mereka.
     * Fungsi ini sekarang PUBLIC agar bisa dipanggil dari routes/web.php
     */
    public function redirectByRole()
    {
        $role = auth()->user()->role;

        switch ($role) {
            case 'super_admin':
                return redirect()->route('superadmin.dashboard');
            case 'admin':
                // Admin langsung diarahkan ke halaman manajemen booking
                return redirect()->route('dashboard');
            case 'user':
                // User biasa (jika ada) diarahkan ke dashboard umum
                return redirect()->route('dashboard');
            default:
                // Jika peran tidak dikenali, logout untuk keamanan
                Auth::logout();
                return redirect()->route('login')->with('error', 'Peran pengguna tidak dikenali.');
        }
    }
}