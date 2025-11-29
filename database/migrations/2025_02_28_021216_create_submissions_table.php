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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id(); // Primary Key (BIGINT, Auto Increment)
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_assignment'); // Tugas yang dikumpulkan
            $table->string('file'); // File tugas yang dikumpulkan
            $table->string('original_name');
            $table->text('catatan')->nullable(); // Catatan tambahan dari mahasiswa
            $table->dateTime('submitted_at')->nullable(); // Waktu pengumpulan tugas
            $table->enum('status', ['submitted', 'graded', 'late'])->default('submitted'); // Status pengumpulan
            $table->integer('nilai')->nullable(); // Nilai tugas (jika sudah dinilai)
            $table->timestamps(); // created_at & updated_at otomatis

            // Foreign Keys
            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_assignment')->references('id')->on('assignments')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
