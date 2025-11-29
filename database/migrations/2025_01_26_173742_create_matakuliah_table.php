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
        Schema::create('matakuliahs', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('id_admin'); 
            $table->unsignedBigInteger('id_dosen'); // Foreign Key ke tabel dosens
            $table->unsignedBigInteger('id_prodi');
            $table->string('kode_mk')->unique(); // Kode mata kuliah (unik)
            $table->string('nama_matakuliah'); // Nama mata kuliah
            $table->integer('sks'); // Jumlah SKS
            $table->text('deskripsi')->nullable(); // Deskripsi mata kuliah
            $table->enum('status_persetujuan', ['setuju', 'tidak setuju', 'menunggu persetujuan'])->default('menunggu persetujuan'); // Status persetujuan
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi ke tabel admins
            $table->foreign('id_admin')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
            // Relasi ke tabel dosens
            $table->foreign('id_dosen')->references('id')->on('dosens')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_prodi')->references('id')->on('prodis')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matakuliahs');
    }
};
