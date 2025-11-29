<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ScheduleController extends Controller
{

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_room'       => 'required|exists:rooms,id',
            'id_matakuliah' => 'required|exists:matakuliahs,id', // Validasi tambahan

            'tanggal'       => 'required|date',
            'waktu_mulai'   => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari'          => 'required|string|max:10',
        ]);

        // Cek apakah hari yang dipilih adalah Sabtu atau Minggu
        if (in_array($validated['hari'], ['Sabtu', 'Minggu'])) {
            return back()->withErrors(['hari' => 'Tidak ada kelas perkuliahan di hari Sabtu dan Minggu.'])->withInput();
        }

        Schedule::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit($id)
{
    $schedule = Schedule::with('room.matakuliah')->findOrFail($id);
    return response()->json($schedule);
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'id_room'       => 'required|exists:rooms,id',
        'id_matakuliah' => 'required|exists:matakuliahs,id',
        'tanggal'       => 'required|date',
        'waktu_mulai'   => 'required|date_format:H:i',
        'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        'hari'          => 'required|string|max:10',
    ]);

    // Cek apakah hari yang dipilih adalah Sabtu atau Minggu
    if (in_array($validated['hari'], ['Sabtu', 'Minggu'])) {
        return back()->withErrors(['hari' => 'Tidak ada kelas perkuliahan di hari Sabtu dan Minggu.'])->withInput();
    }

    $schedule = Schedule::findOrFail($id);
    $schedule->update($validated);

    return redirect()->route('rooms.index')->with('success', 'Jadwal berhasil diperbarui.');
}


public function destroy($id)
{
    $schedule = Schedule::find($id);

    if (!$schedule) {
        return back()->withErrors(['error' => 'Jadwal tidak ditemukan.']);
    }

    $schedule->delete();

    return redirect()->route('rooms.index')->with('success', 'Jadwal berhasil dihapus!');
}



}
