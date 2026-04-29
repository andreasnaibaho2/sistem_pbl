<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Expand ENUM dulu — izinkan KEDUA nilai sekaligus
        DB::statement("ALTER TABLE `pengajuan_proyek_kebutuhan` MODIFY `prodi` ENUM('mekatronika','otomasi','informatika_industri','informatika') NOT NULL");
        DB::statement("ALTER TABLE `proyek_mahasiswa` MODIFY `prodi` ENUM('mekatronika','otomasi','informatika_industri','informatika') NOT NULL");

        // 2. Update data lama
        DB::table('pengajuan_proyek_kebutuhan')
            ->where('prodi', 'informatika_industri')
            ->update(['prodi' => 'informatika']);

        DB::table('proyek_mahasiswa')
            ->where('prodi', 'informatika_industri')
            ->update(['prodi' => 'informatika']);

        // 3. Hapus nilai lama dari ENUM
        DB::statement("ALTER TABLE `pengajuan_proyek_kebutuhan` MODIFY `prodi` ENUM('mekatronika','otomasi','informatika') NOT NULL");
        DB::statement("ALTER TABLE `proyek_mahasiswa` MODIFY `prodi` ENUM('mekatronika','otomasi','informatika') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `pengajuan_proyek_kebutuhan` MODIFY `prodi` ENUM('mekatronika','otomasi','informatika_industri') NOT NULL");
        DB::statement("ALTER TABLE `proyek_mahasiswa` MODIFY `prodi` ENUM('mekatronika','otomasi','informatika_industri') NOT NULL");
    }
};