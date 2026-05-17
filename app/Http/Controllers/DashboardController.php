<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\LogbookMingguan;
use App\Models\LaporanMkPpi;
use App\Models\PenilaianManager;
use App\Models\PenilaianDosen;
use App\Models\PengajuanProyek;
use App\Models\User;
use App\Models\SupervisiMatkul;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isMahasiswa()) {
            return $this->mahasiswaDashboard();
        } elseif ($user->role === 'dosen') {
            $roleAktif = $user->role_aktif ?? 'dosen_pengampu';
            if ($roleAktif === 'manager_proyek') {
                return $this->managerDashboard();
            } else {
                return $this->dosenDashboard();
            }
        } elseif ($user->isManager()) {
            return $this->managerDashboard();
        }

        abort(403);
    }

    private function adminDashboard()
{
    $stats = [
        'total_mahasiswa'    => Mahasiswa::count(),
        'total_proyek_aktif' => PengajuanProyek::where('status', 'approved')->count(),
        'pending_akun'       => User::whereIn('role', ['dosen', 'manager_proyek', 'mahasiswa'])
                                    ->where('status', 'pending')->count(),
        'pending_proyek'     => PengajuanProyek::where('status', 'pending')->count(),
    ];

    // Progress monitoring: mahasiswa yang sudah dinilai lengkap (ada nilai manager + dosen)
    $total        = $stats['total_mahasiswa'] ?: 1;
    $sudahDinilai = Mahasiswa::whereHas('penilaianManager')->whereHas('penilaianDosen')->count();
    $stats['progress_pct'] = round($sudahDinilai / $total * 100);

    // Logbook mingguan belum diverifikasi (terbaru)
    $logbookPending = LogbookMingguan::where('status', 'pending')
        ->with(['mahasiswa'])
        ->latest()
        ->take(5)
        ->get();

    // Laporan belum diverifikasi (terbaru)
    $laporanPending = LaporanMkPpi::where('status_verifikasi', 'pending')
        ->with(['mahasiswa'])
        ->latest()
        ->take(5)
        ->get();

    return view('admin.dashboard', compact('stats', 'logbookPending', 'laporanPending'));
}

    private function managerDashboard()
    {
        $user = auth()->user();

        $proyekList = PengajuanProyek::where('manager_id', $user->id)
            ->with(['mahasiswa.user', 'kebutuhan'])
            ->latest()
            ->get();

        $totalProyek     = $proyekList->count();
        $proyekDisetujui = $proyekList->where('status', 'approved')->count();
        $proyekPending   = $proyekList->where('status', 'pending')->count();
        $totalMahasiswa  = $proyekList->sum(fn($p) => $p->mahasiswa->count());

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

    // Ambil mahasiswa yang disupervisi dosen ini
    $supervisiList = \App\Models\SupervisiMatkul::where('dosen_id', $dosen->id)
        ->with('mahasiswa')
        ->get();

    $mahasiswaIds = $supervisiList->pluck('mahasiswa_id')->unique();
    $totalMahasiswa = $mahasiswaIds->count();

    $laporanMenunggu = LaporanMkPpi::whereIn('mahasiswa_id', $mahasiswaIds)
        ->where('status_verifikasi', 'pending')
        ->count();

    $penilaianSelesai = PenilaianDosen::whereIn('mahasiswa_id', $mahasiswaIds)
        ->whereNotNull('nilai_dosen')
        ->count();

    $laporanTerbaru = LaporanMkPpi::whereIn('mahasiswa_id', $mahasiswaIds)
        ->with(['mahasiswa'])
        ->latest()
        ->take(5)
        ->get();

    $stats = [
        'total_proyek'      => 0,
        'total_mahasiswa'   => $totalMahasiswa,
        'laporan_menunggu'  => $laporanMenunggu,
        'penilaian_selesai' => $penilaianSelesai,
    ];

    // Kirim $proyekList kosong agar view tidak error
    $proyekList = collect();
    $totalProyek = 0;

    return view('dosen.dashboard', compact(
    'proyekList', 'stats',
    'totalProyek', 'totalMahasiswa',
    'laporanMenunggu', 'penilaianSelesai',
    'laporanTerbaru', 'supervisiList'  // ← tambah supervisiList
));
}

    private function mahasiswaDashboard()
{
    $user = auth()->user();
    $mhs  = $user->mahasiswa;

    $proyekDitugaskan = $mhs
        ? PengajuanProyek::whereHas('mahasiswa', function ($q) use ($mhs) {
                $q->where('mahasiswa_id', $mhs->id);
            })
            ->with(['kebutuhan'])
            ->get()
        : collect();

    // Supervisi matkul mahasiswa ini
    $supervisiList = $mhs
        ? \App\Models\SupervisiMatkul::where('mahasiswa_id', $mhs->id)
            ->with(['mataKuliah', 'dosen'])
            ->get()
        : collect();

    $logbook = LogbookMingguan::where('mahasiswa_id', $mhs?->id)
        ->orderBy('minggu_ke')
        ->get();

    $laporan      = LaporanMkPpi::where('mahasiswa_id', $mhs?->id)->get();
    $nilaiManager = PenilaianManager::where('mahasiswa_id', $mhs?->id)->first();
    $nilaiDosen   = PenilaianDosen::where('mahasiswa_id', $mhs?->id)->first();

    $nilaiAkhir = null;
    if ($nilaiManager && $nilaiDosen) {
        $nilaiAkhir = round($nilaiManager->nilai_manager + $nilaiDosen->nilai_dosen, 1);
    } elseif ($nilaiManager) {
        $nilaiAkhir = round($nilaiManager->nilai_manager, 1);
    } elseif ($nilaiDosen) {
        $nilaiAkhir = round($nilaiDosen->nilai_dosen, 1);
    }

    $grade = '-';
    if ($nilaiAkhir !== null) {
        $grade = match(true) {
            $nilaiAkhir >= 85 => 'A',
            $nilaiAkhir >= 75 => 'B',
            $nilaiAkhir >= 65 => 'C',
            $nilaiAkhir >= 55 => 'D',
            default           => 'E',
        };
    }

    $totalLogbook = $logbook->count();
    $totalLaporan = $laporan->count();

    $logbookTerbaru = \App\Models\LogbookHarian::where('mahasiswa_id', $mhs?->id)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    $stats = [
        'total_logbook'    => $totalLogbook,
        'logbook_verified' => $logbook->where('status', 'disetujui')->count(),
        'nilai_akhir'      => $nilaiAkhir ?? 0,
        'grade'            => $grade,
    ];

    return view('mahasiswa.dashboard', compact(
        'mhs', 'logbook', 'laporan',
        'nilaiManager', 'nilaiDosen', 'stats',
        'nilaiAkhir', 'grade', 'totalLogbook', 'totalLaporan',
        'logbookTerbaru', 'proyekDitugaskan', 'supervisiList'
    ));
}
}