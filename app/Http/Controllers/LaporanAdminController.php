<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\PenilaianManager;
use App\Models\PenilaianDosen;
use App\Models\PengajuanProyek;
use Illuminate\Http\Request;

class LaporanAdminController extends Controller
{
    public function index(Request $request)
    {
        $proyekId   = $request->get('proyek_id');
        $proyekList = PengajuanProyek::where('status', 'approved')->orderBy('judul_proyek')->get();

        // Ambil semua mahasiswa dengan relasi penilaian
        $mahasiswaList = Mahasiswa::with(['user', 'penilaianManager', 'penilaianDosen'])->get();

        $mahasiswaRekap = $mahasiswaList->map(function ($m) use ($proyekId) {
            $pm = $proyekId
                ? $m->penilaianManager->where('pengajuan_proyek_id', $proyekId)->first()
                : $m->penilaianManager->first();

            $pd = $m->penilaianDosen->first();

            $nilaiManager = $pm?->nilai_manager ?? null;
            $nilaiDosen   = $pd?->nilai_dosen   ?? null;

            // Skip mahasiswa tanpa penilaian apapun
            if ($nilaiManager === null && $nilaiDosen === null) {
                return null;
            }

            $nilaiAkhir = round(($nilaiManager ?? 0) + ($nilaiDosen ?? 0), 1);

            $grade = match(true) {
                $nilaiAkhir >= 85 => 'A',
                $nilaiAkhir >= 75 => 'B',
                $nilaiAkhir >= 65 => 'C',
                $nilaiAkhir >= 55 => 'D',
                default           => 'E',
            };

            return (object)[
                'nama'          => $m->nama,
                'nim'           => $m->nim,
                'prodi'         => $m->user?->prodi ?? '-',
                'nilai_manager' => $nilaiManager !== null ? round($nilaiManager, 1) : null,
                'nilai_dosen'   => $nilaiDosen   !== null ? round($nilaiDosen, 1)   : null,
                'nilai_akhir'   => $nilaiAkhir,
                'grade'         => $grade,
            ];
        })->filter()->values();

        $totalMahasiswa = Mahasiswa::count();
        $sudahDinilai   = $mahasiswaRekap->filter(fn($r) => $r->nilai_akhir !== null)->count();
        $belumDinilai   = $totalMahasiswa - $sudahDinilai;

        if ($request->get('export')) {
            return $this->exportCsv($mahasiswaRekap);
        }

        return view('laporan.admin', compact(
            'mahasiswaRekap',
            'proyekList',
            'proyekId',
            'totalMahasiswa',
            'sudahDinilai',
            'belumDinilai'
        ));
    }

    private function exportCsv($mahasiswaRekap)
    {
        $filename = 'rekap-nilai-pbl-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($mahasiswaRekap) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No', 'NIM', 'Nama Mahasiswa', 'Program Studi',
                'Nilai Manager (55%)', 'Nilai Dosen (45%)', 'Nilai Akhir', 'Grade'
            ]);

            foreach ($mahasiswaRekap as $i => $r) {
                fputcsv($file, [
                    $i + 1,
                    $r->nim,
                    $r->nama,
                    $r->prodi,
                    $r->nilai_manager ?? '0',
                    $r->nilai_dosen   ?? '0',
                    $r->nilai_akhir   ?? '0',
                    $r->grade         ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}