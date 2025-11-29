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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_attempt'); // Attempt dari mahasiswa
            $table->unsignedBigInteger('id_question'); // Soal yang dijawab
            $table->string('opsi')->nullable(); // Jawaban pilihan ganda (a/b/c/d)
            $table->text('jawaban_teks')->nullable(); // Jawaban untuk soal esai
            $table->boolean('is_correct')->nullable(); // Apakah jawaban benar atau tidak
            $table->integer('nilai')->nullable(); // Nilai untuk jawaban ini
            $table->timestamps();
        
            // Foreign Keys
            $table->foreign('id_attempt')->references('id')->on('quiz_attempts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_question')->references('id')->on('quiz_questions')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
