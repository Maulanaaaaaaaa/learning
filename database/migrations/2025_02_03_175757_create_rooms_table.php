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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('id_matakuliah'); // Foreign key ke tabel matakuliah
            $table->unsignedBigInteger('id_kelas'); // Foreign key ke tabel kelass
            $table->string('nama_ruangan')->nullable(); // Nama ruang kelas (bisa null jika online)
            $table->enum('jenis_kelas', ['offline', 'online'])->default('offline'); // Jenis kelas
            $table->timestamps(); // Menyimpan created_at dan updated_at

            // Menambahkan relasi ke tabel matakuliah
            $table->foreign('id_matakuliah')->references('id')->on('matakuliahs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_kelas')->references('id')->on('kelass')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
