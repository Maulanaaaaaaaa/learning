<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('enrolments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa'); 
            $table->unsignedBigInteger('id_matakuliah'); // Relasi ke tabel matakuliahs
            $table->enum('status', ['setuju', 'tidak setuju', 'menunggu persetujuan'])->default('menunggu persetujuan'); // Status persetujuan
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_matakuliah')->references('id')->on('matakuliahs')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrolments');
    }
};
