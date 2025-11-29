<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use App\Models\Dosen;
use Illuminate\Support\Facades\Storage;
use App\Models\Matakuliah;
use App\Models\Schedule; // Tambahkan ini

class AssignmentController extends Controller
{

    public function index(Request $request)
    {
        $dosen = Dosen::where('id_user', Auth::id())->first();

        if (!$dosen) {
            return back()->with('error', 'Akun ini tidak terhubung dengan data dosen.');
        }

        $rooms = Room::all();
        $matakuliahs = Matakuliah::all();
        $schedules = Schedule::all();
        $assignments = Assignment::with(['room', 'dosen', 'matakuliah', 'schedule']) // Tambahkan schedule
            ->where('id_dosen', $dosen->id)
            ->get();

        return view('assignments.buattugas', compact('rooms', 'matakuliahs', 'assignments', 'schedules'));
    }

    public function store(Request $request)
    {
        $dosen = Dosen::where('id_user', Auth::id())->first();
        if (!$dosen) {
            return back()->with('error', 'Akun ini tidak terhubung dengan data dosen.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_tugas' => 'required|in:materi,quiz,tugas',
            'deadline' => $request->jenis_tugas != 'materi' ? 'required|date_format:Y-m-d\TH:i' : 'nullable',
            'id_room' => 'required|exists:rooms,id',
            'id_matakuliah' => 'required|exists:matakuliahs,id',
            'id_schedule' => 'nullable|exists:schedules,id', // Tambahkan validasi id_schedule
            'file' => 'nullable|mimes:pdf,docx,ppt,pptx|max:10240',
        ]);

        $filePath = null;
        $originalName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->storeAs('materi', time() . '-' . $originalName, 'public');
        }

        Assignment::create([
            'id_dosen' => $dosen->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis_tugas' => $request->jenis_tugas,
            'deadline' => $request->deadline ? \Carbon\Carbon::parse($request->deadline)->format('Y-m-d H:i:s') : null,
            'id_room' => $request->id_room,
            'id_matakuliah' => $request->id_matakuliah,
            'id_schedule' => $request->id_schedule, // Tambahkan id_schedule
            'file' => $filePath,
            'original_name' => $originalName,
        ]);

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $assignment = Assignment::with('room')->findOrFail($id);
        return response()->json([
            'id' => $assignment->id,
            'judul' => $assignment->judul,
            'deskripsi' => $assignment->deskripsi,
            'jenis_tugas' => strtolower($assignment->jenis_tugas),
            'deadline' => $assignment->deadline ? date('Y-m-d\TH:i', strtotime($assignment->deadline)) : null,
            'id_room' => $assignment->id_room,
            'id_matakuliah' => $assignment->id_matakuliah,
            'id_schedule' => $assignment->id_schedule, // Tambahkan id_schedule
            'file' => $assignment->file,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_room' => 'required|exists:rooms,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_tugas' => 'required|in:materi,quiz,tugas',
            'deadline' => $request->jenis_tugas != 'materi' ? 'required|date_format:Y-m-d\TH:i' : 'nullable',
            'id_matakuliah' => 'required|exists:matakuliahs,id',
            'id_schedule' => 'nullable|exists:schedules,id', // Tambahkan validasi id_schedule
            'file' => 'nullable|mimes:pdf,docx,ppt,pptx|max:10240',
        ]);

        $assignment = Assignment::findOrFail($id);

        if ($request->hasFile('file')) {
            if ($assignment->file) {
                Storage::disk('public')->delete($assignment->file);
            }
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->storeAs('materi', time() . '-' . $originalName, 'public');
            $assignment->file = $filePath;
            $assignment->original_name = $originalName;
        }

        $assignment->update([
            'id_room' => $request->id_room,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis_tugas' => $request->jenis_tugas,
            'deadline' => $request->deadline ? \Carbon\Carbon::parse($request->deadline)->format('Y-m-d H:i:s') : null,
            'id_matakuliah' => $request->id_matakuliah,
            'id_schedule' => $request->id_schedule, // Tambahkan id_schedule
        ]);

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);

        if ($assignment->file) {
            Storage::disk('public')->delete($assignment->file);
        }

        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Tugas berhasil dihapus!');
    }
}
