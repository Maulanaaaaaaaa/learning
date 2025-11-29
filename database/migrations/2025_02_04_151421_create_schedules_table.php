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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_room'); // FK ke tabel rooms
            $table->unsignedBigInteger('id_matakuliah'); // Foreign key ke tabel matakuliah
            $table->date('tanggal'); // Tanggal kelas berlangsung
            $table->time('waktu_mulai'); // Waktu mulai kelas
            $table->time('waktu_selesai'); // Waktu selesai kelas
            $table->string('hari'); // Hari dalam seminggu (Senin, Selasa, dll.)
            $table->timestamps();

            // Relasi ke tabel rooms
            $table->foreign('id_room')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_matakuliah')->references('id')->on('matakuliahs')->onDelete('cascade')->onUpdate('cascade');

       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
