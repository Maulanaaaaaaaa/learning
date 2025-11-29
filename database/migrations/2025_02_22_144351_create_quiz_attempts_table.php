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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_quiz'); // Kuis yang dikerjakan
            $table->unsignedBigInteger('id_mahasiswa'); // Mahasiswa yang mengerjakan
            $table->integer('attempt_number'); // Percobaan ke berapa
            $table->integer('total_score')->default(0); // Skor total attempt ini
            $table->timestamp('started_at')->nullable(); // Waktu mulai pengerjaan
            $table->timestamp('ended_at')->nullable(); // Waktu selesai pengerjaan
            $table->timestamps();
        
            // Foreign Keys
            $table->foreign('id_quiz')->references('id')->on('quizzes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
