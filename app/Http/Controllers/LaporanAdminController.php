<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class LaporanAdminController extends Controller
{
    public function index(Request $request)
    {
        $kelasId   = $request->get('kelas_id');
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $query = Penilaian::with(['mahasiswa', 'kelas']);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $penilaianList = $query->orderBy('kelas_id')->get();

        // Map ke format yang dipakai view
        $mahasiswaRekap = $penilaianList->map(function ($p) {
            return (object)[
                'nama'          => $p->mahasiswa?->nama ?? '-',
                'nim'           => $p->mahasiswa?->nim ?? '-',
                'kelas'         => $p->kelas?->nama_kelas ?? $p->kelas?->kode_kelas ?? '-',
                'nilai_manager' => $p->nilai_manager ? round($p->nilai_manager, 1) : null,
                'nilai_dosen'   => $p->nilai_dosen   ? round($p->nilai_dosen, 1)   : null,
                'nilai_akhir'   => $p->nilai_akhir   ? round($p->nilai_akhir, 1)   : null,
                'grade'         => $p->getGrade(),
            ];
        });

        $totalMahasiswa = Mahasiswa::count();
        $sudahDinilai   = $penilaianList->filter(fn($p) => $p->nilai_akhir > 0)->count();
        $belumDinilai   = $totalMahasiswa - $sudahDinilai;

        // Export CSV
        if ($request->get('export')) {
            return $this->exportExcel($penilaianList, $kelasId, $kelasList);
        }

        return view('laporan.admin', compact(
            'mahasiswaRekap',
            'kelasList',
            'kelasId',
            'totalMahasiswa',
            'sudahDinilai',
            'belumDinilai'
        ));
    }

    private function exportExcel($penilaianList, $kelasId, $kelasList)
    {
        $filename = 'rekap-nilai-pbl-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($penilaianList) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No', 'NIM', 'Nama Mahasiswa', 'Kelas',
                'Nilai Manager (55%)', 'Nilai Dosen (45%)', 'Nilai Akhir', 'Grade'
            ]);

            foreach ($penilaianList as $i => $p) {
                fputcsv($file, [
                    $i + 1,
                    $p->mahasiswa?->nim  ?? '-',
                    $p->mahasiswa?->nama ?? '-',
                    $p->kelas?->nama_kelas ?? '-',
                    $p->nilai_manager ? number_format($p->nilai_manager, 2) : '0',
                    $p->nilai_dosen   ? number_format($p->nilai_dosen, 2)   : '0',
                    $p->nilai_akhir   ? number_format($p->nilai_akhir, 2)   : '0',
                    $p->getGrade(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}