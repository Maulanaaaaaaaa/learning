<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Enrolment;
use App\Models\Prodi;
use App\Models\Matakuliah;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Assignment;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    public function enroll($id_matakuliah)
    {
        $user = Auth::user(); // Dapatkan user yang sedang login

        // Pastikan user adalah mahasiswa
        if ($user->role !== 'mahasiswa') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mendaftar.');
        }

        // Cari mahasiswa berdasarkan id_user
        $mahasiswa = Mahasiswa::where('id_user', $user->id)->first();

        // Pastikan mahasiswa ditemukan
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Cek apakah mata kuliah ada
        $matakuliah = Matakuliah::findOrFail($id_matakuliah);

        // Cek apakah mahasiswa sudah terdaftar di mata kuliah ini
        if (Enrolment::where('id_mahasiswa', $mahasiswa->id)->where('id_matakuliah', $id_matakuliah)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar di mata kuliah ini.');
        }

        // Simpan pendaftaran ke tabel enrolments
        Enrolment::create([
            'id_mahasiswa' => $mahasiswa->id, // Pakai id dari tabel mahasiswas, bukan users
            'id_matakuliah' => $id_matakuliah,
            'status' => 'menunggu persetujuan', // Default status menunggu persetujuan
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Menunggu persetujuan dosen.');
    }

    public function Dashboard()
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            return redirect()->route('dashboard')->with('error', 'Anda bukan mahasiswa.');
        }

        // Cari mahasiswa berdasarkan id_user
        $mahasiswa = Mahasiswa::where('id_user', $user->id)->first();

        if (!$mahasiswa) {
            return redirect()->route('dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil daftar mata kuliah yang enrolment-nya sudah "setuju"
        $matakuliahs = Matakuliah::whereHas('enrolments', function ($query) use ($mahasiswa) {
            $query->where('id_mahasiswa', $mahasiswa->id)
                ->where('status', 'setuju'); // Hanya enrolment yang disetujui
        })->get();

        // Ambil assignments: hanya tugas biasa dan quiz yang memiliki data
        $assignments = Assignment::whereIn('id_matakuliah', $matakuliahs->pluck('id'))
            ->where(function ($query) {
                $query->where('jenis_tugas', 'tugas') // Ambil tugas biasa
                    ->orWhere(function ($q) {
                        $q->where('jenis_tugas', 'quiz')
                            ->whereHas('quiz'); // Hanya quiz yang memiliki data
                    });
            })
            ->get();

        return view('db.index', compact('matakuliahs', 'assignments'));
    }


    public function courseMatakuliah(Request $request)
    {
        $id_matakuliah = $request->id_matakuliah;

        // Ambil mata kuliah beserta prodi dan tugas (assignments)
        $matakuliahs = Matakuliah::with(['prodi', 'assignments'])
            ->when($id_matakuliah, function ($query) use ($id_matakuliah) {
                return $query->where('id', $id_matakuliah);
            })
            ->get();

        return view('matakuliah.coursematakuliah', compact('matakuliahs'));
    }




    public function homes()
    {
        $prodis = Prodi::all(); // Ambil semua prodi
        $matakuliahs = Matakuliah::with('admin')->get(); // Ambil semua mata kuliah beserta admin
        return view('matakuliah.homes', compact('prodis', 'matakuliahs'));
    }


    public function index()
    {
        $matakuliahs = Matakuliah::with(['admin', 'dosen', 'prodi'])->get(); // Tambahkan relasi dengan Prodi

        // Ambil semua prodi untuk keperluan dropdown atau filter
        $prodis = Prodi::all();

        return view('kelola.kelolamatkul', compact('matakuliahs', 'prodis'));
    }


    public function indexDosen()
    {
        $userId = Auth::id();
        $dosen = Dosen::where('id_user', $userId)->first();

        if (!$dosen) {
            return redirect()->route('dashboard')->with('error', 'Anda bukan dosen.');
        }

        $matakuliahs = Matakuliah::where('id_dosen', $dosen->id)->with('admin')->get();
        $enrolments = Enrolment::whereHas('matakuliah', function ($query) use ($dosen) {
            $query->where('id_dosen', $dosen->id);
        })->with('mahasiswa', 'matakuliah')->get();



        return view('matakuliah.matakuliah', compact('matakuliahs', 'enrolments'));
    }





    public function store(Request $request)
    {
        $request->validate([
            'id_prodi' => 'required|exists:prodis,id', // Validasi Prodi
            'kode_mk' => 'required|unique:matakuliahs,kode_mk',
            'nama_matakuliah' => 'required',
            'id_admin' => 'required|exists:admins,id',
            'id_dosen' => 'required|exists:dosens,id',
            'sks' => 'required|numeric|min:1',
            'deskripsi' => 'nullable',
        ]);

        Matakuliah::create([
            'id_prodi' => $request->id_prodi,
            'kode_mk' => $request->kode_mk,
            'nama_matakuliah' => $request->nama_matakuliah,
            'id_admin' => $request->id_admin,
            'id_dosen' => $request->id_dosen, // Ini yang menentukan dosen mana yang bisa melihatnya
            'sks' => $request->sks,
            'deskripsi' => $request->deskripsi,
            'status_persetujuan' => 'menunggu persetujuan',
        ]);

        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $matakuliah = Matakuliah::with(['dosen', 'prodi'])->findOrFail($id);
        return response()->json([
            'id' => $matakuliah->id,
            'kode_mk' => $matakuliah->kode_mk,
            'nama_matakuliah' => $matakuliah->nama_matakuliah,
            'sks' => $matakuliah->sks,
            'deskripsi' => $matakuliah->deskripsi,
            'id_dosen' => $matakuliah->id_dosen, // Pastikan id_dosen dikirim
            'id_prodi' => $matakuliah->id_prodi, // Pastikan id_prodi dikirim
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'kode_mk' => 'required|string|max:50',
                'nama_matakuliah' => 'required|string|max:255',
                'sks' => 'required|integer|min:1',
                'deskripsi' => 'nullable|string',
                'id_dosen' => 'required|exists:dosens,id',
                'id_prodi' => 'required|exists:prodis,id',
            ]);

            $matakuliah = Matakuliah::findOrFail($id);
            $matakuliah->update([
                'kode_mk' => $request->kode_mk,
                'nama_matakuliah' => $request->nama_matakuliah,
                'sks' => $request->sks,
                'deskripsi' => $request->deskripsi,
                'id_dosen' => $request->id_dosen,
                'id_prodi' => $request->id_prodi,
            ]);

            return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui mata kuliah: ' . $e->getMessage());
        }
    }


    public function updatePersetujuan(Request $request, $id)
    {
        $request->validate([
            'status_persetujuan' => 'required|in:setuju,tidak setuju',
        ]);

        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->status_persetujuan = $request->status_persetujuan;
        $matakuliah->save();

        return redirect()->route('kelola.matakuliah.dosen')->with('success', 'Status persetujuan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }

    public function approve(Request $request, $id)
    {
        $enrolment = Enrolment::findOrFail($id);
        $enrolment->status = $request->status;
        $enrolment->save();

        return redirect()->back()->with('success', 'Status enrolment berhasil diperbarui.');
    }
}
