<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\PengajuanProyek;
use App\Models\LogbookMingguan;
use App\Models\LaporanMkPpi;
use Illuminate\Http\Request;

class LaporanAdminController extends Controller
{
    public function index(Request $request)
    {
        $proyekId   = $request->get('proyek_id');
        $proyekList = PengajuanProyek::where('status', 'approved')
                        ->orderBy('judul_proyek')->get();

        // Ambil semua mahasiswa dengan relasi aktivitas
        $mahasiswaList = Mahasiswa::with([
            'user',
            'proyek',
            'logbook',         // logbook mingguan
            'logbookHarian',
            'laporan',         // laporan mk/ppi
        ])->get();

        // Filter per proyek jika dipilih
        if ($proyekId) {
            $mahasiswaList = $mahasiswaList->filter(function ($m) use ($proyekId) {
                return $m->proyek->contains('id', (int)$proyekId);
            })->values();
        }

        $mahasiswaProgress = $mahasiswaList->map(function ($m) {
            $proyekAktif      = $m->proyek->where('status', 'approved')->first();
            $totalLogbook     = $m->logbook->count();
            $terverifikasi    = $m->logbook->where('status', 'disetujui')->count();
            $totalHarian      = $m->logbookHarian->count();
            $totalLaporan     = $m->laporan->count();
            $laporanVerified  = $m->laporan->where('status_verifikasi', 'disetujui')->count();

            // Progress logbook dalam persen
            $progressLogbook  = $totalLogbook > 0
                ? round($terverifikasi / $totalLogbook * 100)
                : 0;

            // Status keseluruhan
$status = 'Belum Mulai';
if ($totalLogbook > 0 || $totalLaporan > 0) {
    $allLaporanDone = $totalLaporan > 0 && $laporanVerified === $totalLaporan;
    if ($progressLogbook === 100 && $allLaporanDone) {
        $status = 'Selesai';
    } else {
        $status = 'Berjalan';
    }
}

            return (object)[
                'id'               => $m->id,
                'nama'             => $m->nama,
                'nim'              => $m->nim,
                'prodi'            => $m->user?->prodi ?? '-',
                'proyek'           => $proyekAktif?->judul_proyek ?? '-',
                'total_logbook'    => $totalLogbook,
                'logbook_verified' => $terverifikasi,
                'progress_logbook' => $progressLogbook,
                'total_harian'     => $totalHarian,
                'total_laporan'    => $totalLaporan,
                'laporan_verified' => $laporanVerified,
                'status'           => $status,
            ];
        })->values();

        $totalMahasiswa = $mahasiswaList->count();
        $sudahSelesai   = $mahasiswaProgress->where('status', 'Selesai')->count();
        $sedangBerjalan = $mahasiswaProgress->where('status', 'Berjalan')->count();
        $belumMulai     = $mahasiswaProgress->where('status', 'Belum Mulai')->count();

        if ($request->get('export')) {
            return $this->exportCsv($mahasiswaProgress);
        }

        return view('laporan.admin', compact(
            'mahasiswaProgress',
            'proyekList',
            'proyekId',
            'totalMahasiswa',
            'sudahSelesai',
            'sedangBerjalan',
            'belumMulai'
        ));
    }

    private function exportCsv($mahasiswaProgress)
    {
        $filename = 'monitoring-aktivitas-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($mahasiswaProgress) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No', 'NIM', 'Nama Mahasiswa', 'Program Studi', 'Proyek',
                'Total Logbook', 'Logbook Verified', 'Progress (%)',
                'Total Harian', 'Total Laporan', 'Laporan Verified', 'Status'
            ]);

            foreach ($mahasiswaProgress as $i => $r) {
                fputcsv($file, [
                    $i + 1,
                    $r->nim,
                    $r->nama,
                    $r->prodi,
                    $r->proyek,
                    $r->total_logbook,
                    $r->logbook_verified,
                    $r->progress_logbook . '%',
                    $r->total_harian,
                    $r->total_laporan,
                    $r->laporan_verified,
                    $r->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}