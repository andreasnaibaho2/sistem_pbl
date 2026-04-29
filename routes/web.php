<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PengajuanProyekController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\LaporanAdminController;
use App\Http\Controllers\ApprovalDosenController;
use App\Http\Controllers\MatkulController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin only
    Route::middleware('role:admin')->group(function () {
        // Master Data
        Route::get('admin/matkul', [MatkulController::class, 'index'])->name('admin.matkul.index');
        Route::post('admin/matkul', [MatkulController::class, 'store'])->name('admin.matkul.store');
        Route::delete('admin/matkul/{id}', [MatkulController::class, 'destroy'])->name('admin.matkul.destroy');
        Route::get('admin/prodi', function () {
            return view('admin.data_prodi');
        })->name('admin.prodi.index');

        // Kurikulum
        Route::get('rubrik-penilaian', [PenilaianController::class, 'rubrik'])->name('rubrik.index');
        Route::get('bobot-penilaian', [PenilaianController::class, 'bobot'])->name('bobot.index');

        // Approval
        Route::get('approval-dosen', [ApprovalDosenController::class, 'index'])->name('approval.index');
        Route::patch('approval-dosen/{user}/approve', [ApprovalDosenController::class, 'approve'])->name('approval.approve');
        Route::patch('approval-dosen/{user}/reject', [ApprovalDosenController::class, 'reject'])->name('approval.reject');
        Route::patch('pengajuan-proyek/{pengajuan_proyek}/approve', [PengajuanProyekController::class, 'approve'])->name('pengajuan_proyek.approve');
        Route::patch('pengajuan-proyek/{pengajuan_proyek}/reject', [PengajuanProyekController::class, 'reject'])->name('pengajuan_proyek.reject');

        // Assign Mahasiswa ke Proyek
        Route::get('pengajuan-proyek/{pengajuan_proyek}/assign', [PengajuanProyekController::class, 'assignMahasiswa'])->name('pengajuan_proyek.assign');
        Route::post('pengajuan-proyek/{pengajuan_proyek}/assign', [PengajuanProyekController::class, 'simpanMahasiswa'])->name('pengajuan_proyek.simpan_mahasiswa');

        // Laporan & Monitoring
        Route::get('laporan-admin', [LaporanAdminController::class, 'index'])->name('laporan.admin');
    });

    // Manager Proyek only
    Route::middleware('role:manager_proyek')->group(function () {
        Route::patch('logbook/{logbook}/verifikasi', [LogbookController::class, 'verifikasi'])->name('logbook.verifikasi');
        Route::get('pengajuan-proyek/create', [PengajuanProyekController::class, 'create'])->name('pengajuan_proyek.create');
        Route::post('pengajuan-proyek', [PengajuanProyekController::class, 'store'])->name('pengajuan_proyek.store');
    });

    // Admin & Manager Proyek
    Route::middleware('role:admin,manager_proyek')->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::resource('dosen', DosenController::class);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::post('kelas/{kelas}/assign', [KelasController::class, 'assignMahasiswa'])->name('kelas.assign');
        Route::delete('kelas/{kelas}/remove/{mahasiswa}', [KelasController::class, 'removeMahasiswa'])->name('kelas.remove');
        Route::get('pengajuan-proyek', [PengajuanProyekController::class, 'index'])->name('pengajuan_proyek.index');
        Route::get('proyek-pbl', [PengajuanProyekController::class, 'proyekPbl'])->name('proyek_pbl.index');
    });

    // Detail proyek — bisa diakses semua role (mahasiswa yg ditugaskan, manager, admin)
    Route::get('pengajuan-proyek/{pengajuan_proyek}', [PengajuanProyekController::class, 'show'])->name('pengajuan_proyek.show');

    // Admin, Manager & Dosen
    Route::middleware('role:admin,manager_proyek,dosen')->group(function () {
        Route::resource('penilaian', PenilaianController::class);
    });

    // Dosen only
    Route::middleware('role:dosen')->group(function () {
        Route::patch('laporan/{laporan}/verifikasi', [LaporanController::class, 'verifikasi'])->name('laporan.verifikasi');
    });

    // Mahasiswa only
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('nilai-saya', [NilaiController::class, 'index'])->name('nilai.index');
        Route::get('feedback-hub', [NilaiController::class, 'feedbackHub'])->name('feedback.index');
    });

    // Semua role
    Route::resource('logbook', LogbookController::class);
    Route::resource('laporan', LaporanController::class);
});

require __DIR__.'/auth.php';