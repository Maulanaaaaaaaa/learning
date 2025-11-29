<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class AuthController extends Controller
{
    /**
     * Show the registration form for mahasiswa.
     */
    public function showRegisterMahasiswaForm()
    {
        return view('register');
    }

    /**
     * Handle the registration process for mahasiswa.
     */
    public function registerMahasiswa(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|unique:users,nim|max:15',
            'email' => 'required|email|unique:users,email',
            'no_telp' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
            'role' => 'mahasiswa',
            'status_akun' => 'pending',
            'is_online' => false,
        ]);

        Mahasiswa::create([
            'id_user' => $user->id,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
        ]);

        return redirect('/login')->with('success', 'Registrasi mahasiswa berhasil! Akun Anda menunggu verifikasi.');
    }

    /**
     * Show the registration form for dosen.
     */
    public function showRegisterDosenForm()
    {
        return view('registerdosen');
    }

    /**
     * Handle the registration process for dosen.
     */
    public function registerDosen(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'required|unique:users,nidn|max:10',
            'email' => 'required|email|unique:users,email',
            'no_telp' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'nidn' => $request->nidn,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => bcrypt($request->password),
            'role' => 'dosen',
            'status_akun' => 'pending',
            'is_online' => false,
        ]);

        Dosen::create([
            'id_user' => $user->id,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
        ]);

        return redirect('/login')->with('success', 'Registrasi dosen berhasil! Akun Anda menunggu verifikasi.');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle the login process.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->status_akun !== 'aktif') {
                Auth::logout();
                return redirect('/login')->with('error', 'Akun Anda belum aktif.');
            }

            $user = Auth::user();
            if ($user instanceof User) {
                $user->update(['is_online' => true]);
            }

            if (Auth::user()->role == 'mahasiswa') {
                return redirect('/index');
            } elseif (Auth::user()->role == 'dosen') {
                return redirect('/dosen');
            } elseif (Auth::user()->role == 'admin') {
                return redirect('/admin');
            }

            return redirect('/login')->with('error', 'Role tidak valid.');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    /**
     * Handle the logout process.
     */
    public function logout(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user instanceof User) {
                $user->update(['is_online' => false]);
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with([
            'notifikasi' => 'Anda berhasil logout!',
            'type' => 'success'
        ]);
    }
}
