<?php

namespace App\Http\Controllers;

use App\Models\Kelass;
use App\Models\Room;
use App\Models\Matakuliah;
use App\Models\Schedule;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('matakuliah')->get();
        $schedules = Schedule::with('room')->get();
        $matakuliahs = Matakuliah::all();
        $kelass = Kelass::all(); // Ambil data kelas


        return view('matakuliah.rooms', compact('rooms', 'schedules', 'matakuliahs', 'kelass'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_matakuliah' => 'required|exists:matakuliahs,id',
            'id_kelas' => 'required|exists:kelass,id', // Menambahkan id_kelas
            'jenis_kelas' => 'required|in:offline,online', // Tambahan
            'nama_ruangan' => [
                'nullable',
                'string',
                'max:255',
                'required_if:jenis_kelas,offline', // Tambahkan ini
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->jenis_kelas === 'online' && !empty($value)) {
                        $fail('Nama ruangan tidak boleh diisi untuk kelas online.');
                    }
                },
            ],
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Kelas berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $room = Room::with('matakuliah')->findOrFail($id);
        return response()->json($room);
    }

    public function update(Request $request, $id)
    {

        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'id_matakuliah' => 'required|exists:matakuliahs,id',
            'id_kelas' => 'required|exists:kelass,id', // Menambahkan id_kelas
            'jenis_kelas' => [
                'required',
                'in:offline,online',
                function ($attribute, $value, $fail) use ($room, $request) {
                    if ($room->jenis_kelas === 'offline' && $value === 'online' && !empty($room->nama_ruangan) && empty($request->nama_ruangan)) {
                        // Perbolehkan perubahan jika nama_ruangan sudah dikosongkan
                        $room->nama_ruangan = null;
                        $room->save();
                    }
                },
            ],
            'nama_ruangan' => [
                'nullable',
                'string',
                'max:255',
                'required_if:jenis_kelas,offline', // Tambahkan ini
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->jenis_kelas === 'online' && !empty($value)) {
                        $fail('Nama ruangan tidak boleh diisi untuk kelas online.');
                    }
                },
            ],
        ]);
        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Kelas berhasil diperbarui.');
    }




    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Kelas berhasil dihapus!');
    }
}
