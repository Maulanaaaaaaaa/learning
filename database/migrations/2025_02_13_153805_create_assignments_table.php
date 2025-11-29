<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id(); // Primary Key (BIGINT, Auto Increment)
            $table->unsignedBigInteger('id_dosen');
            $table->unsignedBigInteger('id_room');
            $table->unsignedBigInteger('id_matakuliah');
            $table->unsignedBigInteger('id_schedule')->nullable(); // FK ke schedules (bisa kosong)
            $table->string('judul'); // Judul tugas
            $table->text('deskripsi')->nullable(); // Deskripsi tugas (bisa kosong)
            $table->string('file')->nullable();
            $table->string('original_name')->nullable(); // Nama asli file yang diunggah
            $table->enum('jenis_tugas', ['materi', 'quiz', 'tugas']); // Tipe tugas
            $table->dateTime('deadline')->nullable(); // Batas waktu pengumpulan
            $table->timestamps(); // created_at & updated_at otomatis

            $table->foreign('id_dosen')->references('id')->on('dosens')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_room')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_matakuliah')->references('id')->on('matakuliahs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_schedule')->references('id')->on('schedules')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
