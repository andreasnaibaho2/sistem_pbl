<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\LogbookMingguan;
use App\Models\LaporanMkPpi;
use App\Models\Penilaian;
use App\Models\PengajuanProyek;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isManager()) {
            return $this->managerDashboard();
        } elseif ($user->isDosen()) {
            return $this->dosenDashboard();
        } else {
            return $this->mahasiswaDashboard();
        }
    }

    private function adminDashboard()
    {
        $mahasiswaData = Mahasiswa::with(['user', 'kelas', 'penilaian'])
            ->get()
            ->map(function ($m) {
                $penilaian = $m->penilaian->first();
                return [
                    'id'              => $m->id,
                    'name'            => $m->nama,
                    'nim'             => $m->nim,
                    'prodi'           => $m->user->prodi ?? null,
                    'kelas'           => $m->user->kelas_register ?? null,
                    'nilai_supervisi' => $penilaian ? round($penilaian->nilai_dosen, 1) : null,
                    'nilai_proyek'    => $penilaian ? round($penilaian->nilai_manager, 1) : null,
                ];
            });

        $stats = [
            'total_mahasiswa' => Mahasiswa::count(),
            'total_dosen'     => Dosen::count(),
            'total_kelas'     => Kelas::count(),
            'pending_dosen'   => User::whereIn('role', ['dosen', 'manager_proyek'])
                                    ->where('status', 'pending')->count(),
            'pending_proyek'  => PengajuanProyek::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('mahasiswaData', 'stats'));
    }

    private function managerDashboard()
{
    $user = auth()->user();

    // Ambil proyek milik manager ini
    $proyekList = PengajuanProyek::where('manager_id', $user->id)
        ->with(['mahasiswa.user', 'kebutuhan'])
        ->latest()
        ->get();

    $totalProyek        = $proyekList->count();
    $proyekDisetujui    = $proyekList->where('status', 'approved')->count();
    $proyekPending      = $proyekList->where('status', 'pending')->count();
    $totalMahasiswa     = $proyekList->sum(fn($p) => $p->mahasiswa->count());

    $stats = [
        'total_proyek'     => $totalProyek,
        'proyek_disetujui' => $proyekDisetujui,
        'proyek_pending'   => $proyekPending,
        'total_mahasiswa'  => $totalMahasiswa,
    ];

    return view('manager.dashboard', compact(
        'proyekList', 'stats',
        'totalProyek', 'proyekDisetujui',
        'proyekPending', 'totalMahasiswa'
    ));
}

    private function dosenDashboard()
{
    $user  = auth()->user();
    $dosen = $user->dosen;

    // Ambil proyek yang ada mahasiswa dari prodi dosen ini
    // Dosen bisa verifikasi laporan semua mahasiswa yang di-assign ke proyek
    $proyekList = PengajuanProyek::where('status', 'approved')
        ->with(['mahasiswa.user', 'manager', 'kebutuhan'])
        ->latest()
        ->get();

    $mahasiswaIds = $proyekList->flatMap(fn($p) => $p->mahasiswa->pluck('id'));

    $totalMahasiswa  = $mahasiswaIds->unique()->count();
    $totalProyek     = $proyekList->count();

    $laporanMenunggu = LaporanMkPpi::whereIn('mahasiswa_id', $mahasiswaIds)
        ->where('status_verifikasi', 'pending')
        ->count();

    $penilaianSelesai = Penilaian::whereIn('mahasiswa_id', $mahasiswaIds)
        ->whereNotNull('nilai_dosen')
        ->count();

    $laporanTerbaru = LaporanMkPpi::whereIn('mahasiswa_id', $mahasiswaIds)
        ->with(['mahasiswa'])
        ->latest()
        ->take(5)
        ->get();

    $stats = [
        'total_proyek'      => $totalProyek,
        'total_mahasiswa'   => $totalMahasiswa,
        'laporan_menunggu'  => $laporanMenunggu,
        'penilaian_selesai' => $penilaianSelesai,
    ];

    return view('dosen.dashboard', compact(
        'proyekList', 'stats',
        'totalProyek', 'totalMahasiswa',
        'laporanMenunggu', 'penilaianSelesai',
        'laporanTerbaru'
    ));
}

    private function mahasiswaDashboard()
{
    $user = auth()->user();
    $mhs  = $user->mahasiswa;

    // Ambil proyek yang di-assign ke mahasiswa ini
    $proyekDitugaskan = $mhs
        ? \App\Models\PengajuanProyek::whereHas('mahasiswa', function ($q) use ($mhs) {
                $q->where('mahasiswa_id', $mhs->id);
            })
            ->with(['kebutuhan'])
            ->get()
        : collect();

    $logbook = LogbookMingguan::where('mahasiswa_id', $mhs?->id)
        ->orderBy('minggu_ke')
        ->get();

    $laporan   = LaporanMkPpi::where('mahasiswa_id', $mhs?->id)->get();
    $penilaian = Penilaian::where('mahasiswa_id', $mhs?->id)->first();

    $nilaiAkhir   = $penilaian ? $penilaian->hitungNilaiAkhir() : null;
    $grade        = $penilaian ? $penilaian->getGrade() : '-';
    $totalLogbook = $logbook->count();
    $totalLaporan = $laporan->count();

    $logbookTerbaru = LogbookMingguan::where('mahasiswa_id', $mhs?->id)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $stats = [
        'total_logbook'    => $totalLogbook,
        'logbook_verified' => $logbook->where('status_verifikasi', 'diverifikasi')->count(),
        'nilai_akhir'      => $nilaiAkhir ?? 0,
        'grade'            => $grade,
    ];

    $mahasiswa = $mhs;

    return view('mahasiswa.dashboard', compact(
        'mhs', 'mahasiswa', 'logbook', 'laporan',
        'penilaian', 'stats',
        'nilaiAkhir', 'grade', 'totalLogbook', 'totalLaporan',
        'logbookTerbaru', 'proyekDitugaskan'
    ));
}
}