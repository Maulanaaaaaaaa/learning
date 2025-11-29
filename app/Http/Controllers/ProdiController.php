<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    // Menampilkan semua prodi
    public function index()
{
    $prodis = Prodi::with('admin')->get();
    return view('kelola.kelolaprodi', compact('prodis'));
}


    // Menampilkan form tambah prodi
    public function create()
    {
        return view('prodi.create');
    }

    // Menyimpan prodi baru ke database
     // Pastikan model Admin di-import

     public function store(Request $request)
{
    $request->validate([
        'kode_prodi' => 'required|string|max:10',
        'nama_prodi' => 'required|string|max:255',
        'id_admin' => 'required|exists:admins,id', // Sama seperti store kedua
    ]);

    Prodi::create([
        'kode_prodi' => $request->kode_prodi,
        'nama_prodi' => $request->nama_prodi,
        'id_admin' => $request->id_admin, // ID admin berasal dari request
    ]);

    return redirect()->route('prodi.index')->with('success', 'Prodi berhasil ditambahkan.');
}

     
    


    // Menampilkan form edit prodi
    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('prodi.edit', compact('prodi'));
    }

    // Memperbarui data prodi
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_prodi' => 'required|string|max:10',
            'nama_prodi' => 'required|string|max:255',
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update($request->all());

        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil diperbarui.');
    }

    // Menghapus prodi
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil dihapus.');
    }

    // Menampilkan daftar mata kuliah berdasarkan prodi
    // public function show($id_prodi)
    // {
    //     $prodi = Prodi::findOrFail($id_prodi);
    //     $matakuliahs = Matakuliah::where('id_prodi', $id_prodi)->get();

    //     return view('prodi.show', compact('prodi', 'matakuliahs'));
    // }
}
