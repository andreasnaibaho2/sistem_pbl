<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_mingguan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_logbook', 30)->unique();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa');
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->integer('minggu_ke');
            $table->date('tanggal');
            $table->text('aktivitas');
            $table->text('kendala')->nullable();
            $table->text('solusi')->nullable();
            $table->enum('status_verifikasi', ['pending','disetujui','ditolak'])->default('pending');
            $table->text('catatan_dosen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_mingguan');
    }
};