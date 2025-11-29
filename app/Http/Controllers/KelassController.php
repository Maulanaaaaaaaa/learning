<?php

namespace App\Http\Controllers;

use App\Models\Kelass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan id admin yang login

class KelassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelass = Kelass::all();
        return view('kelas.buatkelas', compact('kelass'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama_kelas' => 'required|string|max:255',
        'semester' => 'required|integer',
        'kode_kelas' => 'required|string|max:10|unique:kelass,kode_kelas',
        'jenis_kelas' => 'required|in:pagi,malam',
        'id_prodi' => 'required|exists:prodis,id', // Validasi id_prodi
        'id_admin' => 'required|exists:admins,id', // Validasi id_admin
    ]);

    Kelass::create([
        'id_prodi' => $request->id_prodi,
        'id_admin' => $request->id_admin,
        'nama_kelas' => $request->nama_kelas,
        'semester' => $request->semester,
        'kode_kelas' => $request->kode_kelas,
        'jenis_kelas' => $request->jenis_kelas,
    ]);

    return redirect()->route('kelass.index')->with('success', 'Kelas berhasil ditambahkan.');
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kelas = Kelass::findOrFail($id);
        return view('kelas.editkelas', compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $kelas = Kelass::findOrFail($id);

    $request->validate([
        'nama_kelas' => 'required|string|max:255',
        'semester' => 'required|integer',
        'kode_kelas' => 'required|string|max:10|unique:kelass,kode_kelas,' . $kelas->id,
        'jenis_kelas' => 'required|in:pagi,malam',
        'id_admin' => 'required|exists:admins,id',
        'id_prodi' => 'required|exists:prodis,id', // Validasi id_prodi
    ]);

    $kelas->update([
        'id_prodi' => $request->id_prodi,
        'id_admin' => $request->id_admin,
        'nama_kelas' => $request->nama_kelas,
        'semester' => $request->semester,
        'kode_kelas' => $request->kode_kelas,
        'jenis_kelas' => $request->jenis_kelas,
    ]);

    return redirect()->route('kelass.index')->with('success', 'Kelas berhasil diperbarui.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kelas = Kelass::findOrFail($id);
        $kelas->delete();
        return redirect()->route('kelass.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
