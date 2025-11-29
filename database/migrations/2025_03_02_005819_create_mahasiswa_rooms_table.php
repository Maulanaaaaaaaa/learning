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
        Schema::create('mahasiswa_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa'); // Foreign key ke tabel mahasiswas
            $table->unsignedBigInteger('id_room'); // Foreign key ke tabel rooms
            $table->timestamps();

            // Menambahkan foreign key constraints
            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_room')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_rooms');
    }
};
