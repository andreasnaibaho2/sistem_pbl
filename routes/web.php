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
use App\Http\Controllers\LogbookHarianController;
use App\Http\Controllers\LogbookMingguanController;
use App\Http\Controllers\Admin\SupervisiMatkulController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function () {

    // ── Pilih Role (khusus dosen) ──
    Route::get('/pilih-role', function () {
    $user = auth()->user();
    if ($user->role !== 'dosen') {
        return redirect('/dashboard');
    }

    $akses = $user->akses_role ?? 'keduanya';
    if ($akses === 'dosen_pengampu') {
        $user->update(['role_aktif' => 'dosen_pengampu']);
        return redirect('/dashboard');
    }
    if ($akses === 'manager_proyek') {
        $user->update(['role_aktif' => 'manager_proyek']);
        return redirect('/dashboard');
    }

    return view('auth.pilih_role');
})->name('pilih.role');

    Route::post('/pilih-role', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'role_aktif' => 'required|in:manager_proyek,dosen_pengampu',
        ]);
        auth()->user()->update(['role_aktif' => $request->role_aktif]);
        return redirect('/dashboard');
    })->name('pilih.role.simpan');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/matkul', [MatkulController::class, 'index'])->name('admin.matkul.index');
        Route::post('admin/matkul', [MatkulController::class, 'store'])->name('admin.matkul.store');
        Route::delete('admin/matkul/{id}', [MatkulController::class, 'destroy'])->name('admin.matkul.destroy');
        Route::get('admin/prodi', function () {
            return view('admin.data_prodi');
        })->name('admin.prodi.index');

        Route::get('rubrik-penilaian', [PenilaianController::class, 'rubrik'])->name('rubrik.index');
        Route::get('bobot-penilaian', [PenilaianController::class, 'bobot'])->name('bobot.index');

        Route::get('approval-dosen', [ApprovalDosenController::class, 'index'])->name('approval.index');
        Route::patch('approval-dosen/{user}/approve', [ApprovalDosenController::class, 'approve'])->name('approval.approve');
        Route::patch('approval-dosen/{user}/approve-role', [ApprovalDosenController::class, 'approveWithRole'])->name('approval.approve.role');
        Route::patch('approval-dosen/{user}/reject', [ApprovalDosenController::class, 'reject'])->name('approval.reject');
        Route::patch('pengajuan-proyek/{pengajuan_proyek}/approve', [PengajuanProyekController::class, 'approve'])->name('pengajuan_proyek.approve');
        Route::delete('pengajuan-proyek/{pengajuan_proyek}', [PengajuanProyekController::class, 'destroy'])->name('pengajuan_proyek.destroy');
        Route::patch('pengajuan-proyek/{pengajuan_proyek}/reject', [PengajuanProyekController::class, 'reject'])->name('pengajuan_proyek.reject');

        Route::get('pengajuan-proyek/{pengajuan_proyek}/assign', [PengajuanProyekController::class, 'assignMahasiswa'])->name('pengajuan_proyek.assign');
        Route::post('pengajuan-proyek/{pengajuan_proyek}/assign', [PengajuanProyekController::class, 'simpanMahasiswa'])->name('pengajuan_proyek.simpan_mahasiswa');

        Route::get('laporan-admin', [LaporanAdminController::class, 'index'])->name('laporan.admin');

        // Supervisi Matkul
        Route::get('admin/supervisi', [SupervisiMatkulController::class, 'index'])->name('admin.supervisi.index');
        Route::get('admin/supervisi/create', [SupervisiMatkulController::class, 'create'])->name('admin.supervisi.create');
        Route::post('admin/supervisi', [SupervisiMatkulController::class, 'store'])->name('admin.supervisi.store');
        Route::delete('admin/supervisi/{supervisi}', [SupervisiMatkulController::class, 'destroy'])->name('admin.supervisi.destroy');
    });

    // Manager Proyek only
    Route::middleware('role:manager_proyek,dosen')->group(function () {
        Route::patch('logbook/{logbook}/verifikasi', [LogbookController::class, 'verifikasi'])->name('logbook.verifikasi');
        Route::get('pengajuan-proyek/create', [PengajuanProyekController::class, 'create'])->name('pengajuan_proyek.create');
        Route::post('pengajuan-proyek', [PengajuanProyekController::class, 'store'])->name('pengajuan_proyek.store');

        Route::get('logbook-mingguan/verifikasi', [LogbookMingguanController::class, 'daftarVerifikasi'])->name('logbook_mingguan.daftar_verifikasi');
    });

    // Admin & Manager Proyek
    Route::middleware('role:admin,manager_proyek,dosen')->group(function () {
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::resource('dosen', DosenController::class);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::post('kelas/{kelas}/assign', [KelasController::class, 'assignMahasiswa'])->name('kelas.assign');
        Route::delete('kelas/{kelas}/remove/{mahasiswa}', [KelasController::class, 'removeMahasiswa'])->name('kelas.remove');
        Route::get('pengajuan-proyek', [PengajuanProyekController::class, 'index'])->name('pengajuan_proyek.index');
        Route::get('proyek-pbl', [PengajuanProyekController::class, 'proyekPbl'])->name('proyek_pbl.index');
    });

    // Detail proyek
    Route::get('pengajuan-proyek/{pengajuan_proyek}', [PengajuanProyekController::class, 'show'])->name('pengajuan_proyek.show');

    // Penilaian — Manager
    Route::middleware('role:manager_proyek,dosen')->group(function () {
        Route::get('penilaian/manager/create', [PenilaianController::class, 'createManager'])->name('penilaian.manager.create');
        Route::post('penilaian/manager', [PenilaianController::class, 'storeManager'])->name('penilaian.manager.store');
    });

    // Penilaian — Dosen Pengampu
    Route::middleware('role:dosen')->group(function () {
        Route::get('penilaian/dosen/create', [PenilaianController::class, 'createDosen'])->name('penilaian.dosen.create');
        Route::post('penilaian/dosen', [PenilaianController::class, 'storeDosen'])->name('penilaian.dosen.store');
    });

    // Penilaian — Index (semua role)
    Route::middleware('role:admin,manager_proyek,dosen,mahasiswa')->group(function () {
        Route::get('penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    });

    // Verifikasi Laporan (dosen)
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

    // Logbook Harian - Mahasiswa
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('logbook-harian', [LogbookHarianController::class, 'index'])->name('logbook_harian.index');
        Route::get('logbook-harian/create', [LogbookHarianController::class, 'create'])->name('logbook_harian.create');
        Route::post('logbook-harian', [LogbookHarianController::class, 'store'])->name('logbook_harian.store');
        Route::get('logbook-harian/{logbook_harian}', [LogbookHarianController::class, 'show'])->name('logbook_harian.show');
        Route::get('logbook-harian/{logbook_harian}/edit', [LogbookHarianController::class, 'edit'])->name('logbook_harian.edit');
        Route::put('logbook-harian/{logbook_harian}', [LogbookHarianController::class, 'update'])->name('logbook_harian.update');
        Route::delete('logbook-harian/{logbook_harian}', [LogbookHarianController::class, 'destroy'])->name('logbook_harian.destroy');
        Route::get('logbook-harian-rekap', [LogbookHarianController::class, 'rekapMingguan'])->name('logbook_harian.rekap');
    });
    Route::patch('logbook-harian/{logbook_harian}/verifikasi', [LogbookHarianController::class, 'verifikasi'])->name('logbook_harian.verifikasi');

    // ── Logbook Mingguan Show — di luar group, akses semua role ──
    Route::get('logbook-mingguan/{logbookMingguan}', [LogbookMingguanController::class, 'show'])
        ->name('logbook_mingguan.show')
        ->middleware('role:mahasiswa,manager_proyek,admin,dosen');

    // Logbook Mingguan - Mahasiswa (tanpa show)
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('logbook-mingguan', [LogbookMingguanController::class, 'index'])->name('logbook_mingguan.index');
        Route::post('logbook-mingguan/generate', [LogbookMingguanController::class, 'generate'])->name('logbook_mingguan.generate');
    });

    // Logbook Mingguan - Manager & Admin (tanpa show)
    Route::middleware('role:manager_proyek,admin,dosen')->group(function () {
        Route::patch('logbook-mingguan/{logbookMingguan}/verifikasi', [LogbookMingguanController::class, 'verifikasi'])->name('logbook_mingguan.verifikasi');
    });
});

require __DIR__.'/auth.php';