<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;

use App\Models\Mahasiswa;
use App\Models\Assignment;

class SubmissionController extends Controller
{

    public function updateNilai(Request $request, $id)
{
    $submission = Submission::findOrFail($id);

    $request->validate([
        'nilai' => 'nullable|integer|min:0|max:100',
    ]);

    $submission->nilai = $request->nilai;
    $submission->status = 'graded'; // Ubah status ke "graded" setelah diberi nilai
    $submission->save();

    return redirect()->back()->with('success', 'Nilai berhasil diperbarui!');
}

    public function submissionIndex()
    {
        $submissions = Submission::with(['mahasiswa', 'assignment'])->get();
        return view('assignments.submissions', compact('submissions'));
    }

    public function index()
    {
        $submissions = Submission::with(['mahasiswa', 'assignment'])->get();
        return view('matakuliah.coursematakuliah', compact('submissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswas,id',
            'id_assignment' => 'required|exists:assignments,id',
            'file' => 'required|file|mimes:pdf,docx,ppt,pptx|max:10240',
            'catatan' => 'nullable|string',
        ]);
    
        $assignment = Assignment::findOrFail($request->id_assignment);
        $submittedAt = now(); // Waktu saat mahasiswa mengumpulkan tugas
    
        // Menentukan status berdasarkan deadline
        $status = $submittedAt > $assignment->deadline ? 'late' : 'submitted';
    
        // Simpan file ke storage/submissions
        $originalName = $request->file('file')->getClientOriginalName(); // Ambil nama asli file
        $filePath = $request->file('file')->storeAs('submissions', time() . '-' . $originalName, 'public');
    
        Submission::create([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_assignment' => $request->id_assignment,
            'file' => $filePath, // Path file di storage
            'original_name' => $originalName, // Simpan nama asli file
            'catatan' => $request->catatan,
            'submitted_at' => $submittedAt,
            'status' => $status,
        ]);
    
        return redirect()->back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
    


    public function edit($id)
    {
        $submission = Submission::findOrFail($id);
        return view('submissions.edit', compact('submission'));
    }

    public function update(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);
    
        $request->validate([
            'file' => 'nullable|file|mimes:pdf,docx,ppt,pptx|max:10240',
            'catatan' => 'nullable|string',
        ]);
    
        // Perbarui waktu pengumpulan saat tugas diedit
        $submission->submitted_at = now();
    
        // Jika ada file baru yang diunggah
        if ($request->hasFile('file')) {
            $originalName = $request->file('file')->getClientOriginalName(); // Ambil nama asli file
            $filePath = $request->file('file')->storeAs('submissions', time() . '-' . $originalName, 'public');
            $submission->file = $filePath;
            $submission->original_name = $originalName; // Simpan nama asli baru
        }
    
        // Update catatan jika ada
        if ($request->filled('catatan')) {
            $submission->catatan = $request->catatan;
        }
    
        // Cek status baru berdasarkan deadline
        $assignment = Assignment::findOrFail($submission->id_assignment);
        $submission->status = $submission->submitted_at > $assignment->deadline ? 'late' : 'submitted';
    
        $submission->save();
    
        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }
    


    public function destroy($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->delete();
        return redirect()->back()->with('success', 'Submission berhasil dihapus!');
    }
}
