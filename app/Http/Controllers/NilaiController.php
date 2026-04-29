<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Kelas;
use App\Models\LogbookMingguan;
use App\Models\LaporanMkPpi;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return view('mahasiswa.nilai', ['penilaian' => null, 'kelas' => null]);
        }

        $kelasList = $mahasiswa->kelas()->with(['mataKuliah', 'dosen'])->get();

        $penilaianList = Penilaian::where('mahasiswa_id', $mahasiswa->id)
            ->with(['kelas.mataKuliah', 'dosen'])
            ->get();

        return view('mahasiswa.nilai', compact('kelasList', 'penilaianList', 'mahasiswa'));
    }

    public function feedbackHub()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return view('mahasiswa.feedback', [
                'mahasiswa'     => null,
                'feedbackList'  => collect(),
                'totalFeedback' => 0,
            ]);
        }

        $feedbackList = collect();

        // 1. Feedback dari catatan penilaian (Manager & Dosen)
        $penilaianList = Penilaian::where('mahasiswa_id', $mahasiswa->id)
            ->with(['kelas.mataKuliah', 'dosen'])
            ->get();

        foreach ($penilaianList as $p) {
            $matkulNama = $p->kelas?->mataKuliah?->nama_matkul ?? 'Mata Kuliah';

            if (!empty($p->catatan_manager)) {
                $feedbackList->push([
                    'tipe'      => 'penilaian',
                    'sumber'    => 'Manager Proyek',
                    'icon'      => 'manage_accounts',
                    'color'     => 'primary',
                    'konteks'   => $matkulNama,
                    'pesan'     => $p->catatan_manager,
                    'tanggal'   => $p->updated_at,
                    'badge'     => 'Penilaian',
                ]);
            }

            if (!empty($p->catatan_dosen)) {
                $dosenNama = $p->dosen?->nama_dosen ?? 'Dosen Pengampu';
                $feedbackList->push([
                    'tipe'      => 'penilaian',
                    'sumber'    => $dosenNama,
                    'icon'      => 'school',
                    'color'     => 'secondary',
                    'konteks'   => $matkulNama,
                    'pesan'     => $p->catatan_dosen,
                    'tanggal'   => $p->updated_at,
                    'badge'     => 'Penilaian',
                ]);
            }
        }

        // 2. Feedback dari catatan logbook
        $logbookList = LogbookMingguan::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('catatan_dosen')
            ->where('catatan_dosen', '!=', '')
            ->with('kelas.mataKuliah')
            ->orderBy('minggu_ke')
            ->get();

        foreach ($logbookList as $lb) {
            $matkulNama = $lb->kelas?->mataKuliah?->nama_matkul ?? 'Mata Kuliah';
            $feedbackList->push([
                'tipe'      => 'logbook',
                'sumber'    => 'Manager Proyek',
                'icon'      => 'book',
                'color'     => 'amber',
                'konteks'   => $matkulNama . ' — Minggu ' . $lb->minggu_ke,
                'pesan'     => $lb->catatan_dosen,
                'tanggal'   => $lb->updated_at,
                'badge'     => 'Logbook',
            ]);
        }

        // 3. Feedback dari catatan laporan
        $laporanList = LaporanMkPpi::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('catatan_verifikasi')
            ->where('catatan_verifikasi', '!=', '')
            ->with('kelas.mataKuliah')
            ->get();

        foreach ($laporanList as $lp) {
            $matkulNama = $lp->kelas?->mataKuliah?->nama_matkul ?? 'Mata Kuliah';
            $feedbackList->push([
                'tipe'      => 'laporan',
                'sumber'    => 'Dosen Pengampu',
                'icon'      => 'description',
                'color'     => 'rose',
                'konteks'   => $matkulNama . ' — ' . $lp->jenis_laporan,
                'pesan'     => $lp->catatan_verifikasi,
                'tanggal'   => $lp->updated_at,
                'badge'     => 'Laporan',
            ]);
        }

        // Urutkan semua feedback: terbaru dulu
        $feedbackList = $feedbackList->sortByDesc('tanggal')->values();

        $totalFeedback = $feedbackList->count();
        $feedbackPerTipe = [
            'penilaian' => $feedbackList->where('tipe', 'penilaian')->count(),
            'logbook'   => $feedbackList->where('tipe', 'logbook')->count(),
            'laporan'   => $feedbackList->where('tipe', 'laporan')->count(),
        ];

        return view('mahasiswa.feedback', compact(
            'mahasiswa',
            'feedbackList',
            'totalFeedback',
            'feedbackPerTipe'
        ));
    }
}