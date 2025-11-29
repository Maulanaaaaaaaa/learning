<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder untuk Admin
        $admin = User::create([
            'nama' => 'Maulana Ganteng 123 (Admin)',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'no_telp' => '085673651234',
            'role' => 'admin',
            'status_akun' => 'aktif',
        ]);

        Admin::create([
            'id_user' => $admin->id, // Foreign key dari tabel `users`
            'nama' => $admin->nama,
            'email' => $admin->email,
            'no_telp' => $admin->no_telp,
        ]);

        // Seeder untuk Mahasiswa
        $mahasiswa = User::create([
            'nim' => '2101234567', // NIM Mahasiswa
            'nama' => 'Maulana Ganteng 123 (Mahasiswa)',
            'email' => 'pengguna2@gmail.com',
            'password' => Hash::make('12345678'),
            'no_telp' => '081234567890',
            'role' => 'mahasiswa',
            'status_akun' => 'aktif',
        ]);

        Mahasiswa::create([
            'id_user' => $mahasiswa->id, // Foreign key dari tabel `users`
            'nama' => $mahasiswa->nama,
            'email' => $mahasiswa->email,
            'no_telp' => $mahasiswa->no_telp,
        ]);

        // Seeder untuk Dosen
        $dosen = User::create([
            'nidn' => '0012345678', // NIDN Dosen
            'nama' => 'Maulana Ganteng 123 (Dosen)',
            'email' => 'pengguna3@gmail.com',
            'password' => Hash::make('12345678'),
            'no_telp' => '085612345678',
            'role' => 'dosen',
            'status_akun' => 'aktif',
        ]);

        Dosen::create([
            'id_user' => $dosen->id, // Foreign key dari tabel `users`
            'nama' => $dosen->nama,
            'email' => $dosen->email,
            'no_telp' => $dosen->no_telp,
        ]);
    }
}
