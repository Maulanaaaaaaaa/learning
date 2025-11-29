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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_quiz');
            $table->text('pertanyaan');
            $table->enum('jenis_pertanyaan', ['pilihan_ganda', 'esay']);
            $table->json('opsi_jawaban')->nullable(); // Hanya untuk pilihan ganda
            $table->string('jawaban_benar')->nullable(); // Bisa index opsi (a/b/c/d) daripada teks langsung
            $table->integer('bobot_nilai')->default(1);
            $table->integer('urutan')->default(0); // Urutan soal dalam quiz
            $table->timestamps();
        
            // Foreign Key
            $table->foreign('id_quiz')->references('id')->on('quizzes')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
