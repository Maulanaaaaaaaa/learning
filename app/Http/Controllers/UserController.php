<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function getUsersStatus(): JsonResponse
    {
        $mahasiswaOnline = User::where('role', 'mahasiswa')->where('is_online', true)->get();
        $mahasiswaOffline = User::where('role', 'mahasiswa')->where('is_online', false)->get();
        $dosenOnline = User::where('role', 'dosen')->where('is_online', true)->get();
        $dosenOffline = User::where('role', 'dosen')->where('is_online', false)->get();

        return response()->json([
            'mahasiswa_online' => $mahasiswaOnline,
            'mahasiswa_offline' => $mahasiswaOffline,
            'dosen_online' => $dosenOnline,
            'dosen_offline' => $dosenOffline,
        ]);
    }

    /**
     * Display a listing of the users with search functionality.
     * Redirects to different views based on role.
     */
    public function index(Request $request)
    {
        $role = $request->route()->getPrefix() === '/keloladosen' ? 'dosen' : 'mahasiswa';
        $search = $request->input('search');

        $users = User::where('role', $role)
            ->when($search, function ($query, $search) use ($role) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere($role === 'mahasiswa' ? 'nim' : 'nidn', 'like', "%{$search}%");
            })
            ->paginate(10);

        $view = $role === 'dosen' ? 'kelola.keloladosen' : 'kelola.kelola';
        return view($view, compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('kelola.edit', compact('user'));
    }

    /**
     * Update the account status of the specified user.
     */
    public function updateStatus(Request $request, $id)
    {
        $role = $request->route()->getPrefix() === '/keloladosen' ? 'dosen' : 'mahasiswa';

        $user = User::where('role', $role)->findOrFail($id);
        $user->update(['status_akun' => $request->input('status_akun')]);

        $redirectRoute = $role === 'dosen' ? 'keloladosen.index' : 'kelola.index';
        return redirect()->route($redirectRoute)->with('success', ucfirst($role) . ' status berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $role = $request->route()->getPrefix() === '/keloladosen' ? 'dosen' : 'mahasiswa';

        $validated = $request->validate([
            ($role === 'mahasiswa' ? 'nim' : 'nidn') => 'required|unique:users,' . ($role === 'mahasiswa' ? 'nim' : 'nidn'),
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_telp' => 'nullable|string|max:15',
            'password' => 'required|min:8|confirmed', // Tambahkan konfirmasi password
        ]);

        // Simpan ke tabel users
        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_telp' => $request->input('no_telp'),
            'password' => bcrypt($validated['password']), // Hash password
            'role' => $role,
            'status_akun' => 'pending',
            'nim' => $role === 'mahasiswa' ? $validated['nim'] : null,
            'nidn' => $role === 'dosen' ? $validated['nidn'] : null,
            'is_online' => false, // Default offline saat pendaftaran
        ]);

        // Simpan ke tabel mahasiswa atau dosen
        if ($role === 'mahasiswa') {
            Mahasiswa::create([
                'id_user' => $user->id,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_telp' => $request->input('no_telp'),
            ]);
        } else {
            Dosen::create([
                'id_user' => $user->id,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_telp' => $request->input('no_telp'),
            ]);
        }

        return redirect()->route($role === 'dosen' ? 'keloladosen.index' : 'kelola.index')
            ->with('success', ucfirst($role) . ' berhasil ditambahkan.');
    }

    /**
     * Update online status of the user.
     */
    public function updateOnlineStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_online' => $request->input('is_online')]);

        return response()->json(['message' => 'Status online berhasil diperbarui.']);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $role = request()->route()->getPrefix() === '/keloladosen' ? 'dosen' : 'mahasiswa';
        $user = User::where('role', $role)->findOrFail($id);
        $user->delete();

        $redirectRoute = $role === 'dosen' ? 'keloladosen.index' : 'kelola.index';
        return redirect()->route($redirectRoute)->with('success', ucfirst($role) . ' berhasil dihapus.');
    }
}
