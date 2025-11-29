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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id(); // Primary Key (BIGINT, Auto Increment)
            $table->unsignedBigInteger('id_assignment'); // Foreign Key ke tabel assignments
            $table->string('judul'); // Judul quiz
            $table->text('deskripsi')->nullable(); // Deskripsi quiz (bisa kosong)
            $table->enum('jenis_soal', ['pilihan_ganda', 'esay', 'campuran']); // Jenis soal quiz
            $table->dateTime('waktu_pengerjaan');
            $table->integer('durasi')->default(30); // Durasi dalam menit, default 30
            $table->string('quiz_password'); // Password untuk quiz (bisa kosong)
            $table->integer('attempt_limit')->default(1); // Default 1 kali
            $table->timestamps(); // created_at & updated_at otomatis

            // Foreign Key Constraints
            $table->foreign('id_assignment')->references('id')->on('assignments')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};