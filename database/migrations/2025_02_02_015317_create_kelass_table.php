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
        Schema::create('kelass', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_admin'); 
            $table->unsignedBigInteger('id_prodi'); // Foreign key ke tabel prodi
            $table->string('nama_kelas'); // Misal: "A", "B", "C"
            $table->integer('semester'); // Misal: 1, 2, 3
            $table->string('kode_kelas'); // Misal: "1A", "2B", "3C"
            $table->enum('jenis_kelas', ['pagi', 'malam'])->default('pagi'); // Menentukan shift
            $table->timestamps();

            $table->foreign('id_admin')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_prodi')->references('id')->on('prodis')->onDelete('cascade')->onUpdate('cascade'); // Relasi ke prodi

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelass');
    }
};
