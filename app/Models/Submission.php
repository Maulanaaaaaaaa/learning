<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $table = 'submissions';

    protected $fillable = [
        'id_mahasiswa',
        'id_assignment',
        'file',
        'catatan',
        'submitted_at',
        'status',
        'nilai',
        'original_name',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'id_assignment');
    }
}
