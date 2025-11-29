<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\MahasiswaRoom;
use App\Models\Room;

class MahasiswaRoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        $mahasiswa = Mahasiswa::all();
        $mahasiswaRooms = MahasiswaRoom::with('mahasiswa', 'room')->get();

        return view('kelas.mahasiswarooms', compact('rooms', 'mahasiswa', 'mahasiswaRooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswas,id',
            'id_room' => 'required|exists:rooms,id',
        ]);

        $existing = MahasiswaRoom::where('id_mahasiswa', $request->id_mahasiswa)
                    ->where('id_room', $request->id_room)
                    ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Mahasiswa sudah terdaftar di kelas ini.');
        }

        MahasiswaRoom::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_room' => $request->id_room,
        ]);

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan ke dalam kelas.');
    }

    public function edit($id)
    {
        $mahasiswaRoom = MahasiswaRoom::findOrFail($id);
        $rooms = Room::all();
        $mahasiswa = Mahasiswa::all();
        return view('kelas.editMahasiswaRoom', compact('mahasiswaRoom', 'rooms', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswas,id',
            'id_room' => 'required|exists:rooms,id',
        ]);

        $mahasiswaRoom = MahasiswaRoom::findOrFail($id);
        $mahasiswaRoom->update([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_room' => $request->id_room,
        ]);

        return redirect()->route('mahasiswarooms.index')->with('success', 'Data mahasiswa di kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mahasiswaRoom = MahasiswaRoom::findOrFail($id);
        $mahasiswaRoom->delete();

        return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus dari kelas.');
    }
}
