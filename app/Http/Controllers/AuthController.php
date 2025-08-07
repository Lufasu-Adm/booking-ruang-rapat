<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse; // Tambahkan ini

/**
 * Class AuthController
 * @package App\Http\Controllers
 *
 * Controller yang menangani semua fungsionalitas autentikasi pengguna.
 * Termasuk registrasi, login, dan logout.
 */
class AuthController extends Controller
{
    /**
     * AuthController constructor.
     * Menerapkan middleware untuk membatasi akses ke metode-metode autentikasi.
     * Metode login dan register hanya bisa diakses oleh tamu (guest).
     * Metode logout hanya bisa diakses oleh pengguna yang sudah login (auth).
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Menampilkan halaman form registrasi.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showRegister()
    {
        // Jika form register perlu daftar divisi, uncomment baris ini
        // $divisions = \App\Models\Division::all();
        // return view('register', compact('divisions'));
        return view('register');
    }

    /**
     * Memproses data dari form registrasi dan membuat pengguna baru.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function doRegister(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
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
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Memproses percobaan login dari pengguna.
     * Jika kredensial valid, akan mengarahkan pengguna ke dashboard yang sesuai.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'Kombinasi email/username dan password salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses logout pengguna.
     *
     * @param Request $request
     * @return RedirectResponse
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
     *
     * @return RedirectResponse
     */
    public function redirectByRole()
    {
        $role = auth()->user()->role;

        switch ($role) {
            case 'super_admin':
                return redirect()->route('superadmin.dashboard');
            case 'admin':
                return redirect()->route('dashboard');
            case 'user':
                return redirect()->route('dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Peran pengguna tidak dikenali.');
        }
    }
}