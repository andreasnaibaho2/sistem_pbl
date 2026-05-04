<?php

namespace App\Http\Controllers;

use App\Models\PenilaianManager;
use App\Models\PenilaianDosen;
use App\Models\PengajuanProyek;
use App\Models\SupervisiMatkul;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    // =====================================================================
    // INDEX — daftar penilaian sesuai role
    // =====================================================================
    public function index()
    {
        $user = auth()->user();

        if ($user->isManager()) {
            // Manager lihat penilaian_manager miliknya
            $proyekIds = PengajuanProyek::where('manager_id', $user->id)->pluck('id');
            $penilaian = PenilaianManager::with(['mahasiswa', 'pengajuanProyek'])
                ->whereIn('pengajuan_proyek_id', $proyekIds)
                ->get();
            return view('penilaian.index_manager', compact('penilaian'));

        } elseif ($user->roleAktifDosen()) {
            // Dosen (mode dosen_pengampu) lihat penilaian_dosen miliknya
            $penilaian = PenilaianDosen::with(['mahasiswa', 'supervisiMatkul.mataKuliah'])
                ->where('dosen_id', $user->dosen->id)
                ->get();
            return view('penilaian.index_dosen', compact('penilaian'));

        } elseif ($user->isMahasiswa()) {
            $mahasiswa   = $user->mahasiswa;
            $nilaiManager = PenilaianManager::where('mahasiswa_id', $mahasiswa->id)->first();
            $nilaiDosen   = PenilaianDosen::with('supervisiMatkul.mataKuliah')
                ->where('mahasiswa_id', $mahasiswa->id)->get();
            return view('penilaian.index_mahasiswa', compact('nilaiManager', 'nilaiDosen', 'mahasiswa'));

        } else {
            // Admin — lihat semua
            $penilaianManager = PenilaianManager::with(['mahasiswa', 'pengajuanProyek'])->get();
            $penilaianDosen   = PenilaianDosen::with(['mahasiswa', 'supervisiMatkul.mataKuliah'])->get();
            return view('penilaian.index_admin', compact('penilaianManager', 'penilaianDosen'));
        }
    }

    // =====================================================================
    // CREATE MANAGER — form input penilaian oleh Manager Proyek
    // =====================================================================
    public function createManager()
{
    $user = auth()->user();

    if (!$user->isManager()) {
        abort(403);
    }

    $proyekList = PengajuanProyek::where('manager_id', $user->id)
        ->where('status', 'approved')
        ->with(['mahasiswa'])
        ->get();

    

    return view('penilaian.create_manager', compact('proyekList'));
}

    // =====================================================================
    // STORE MANAGER
    // =====================================================================
    public function storeManager(Request $request)
    {
        $user = auth()->user();

        if (!$user->isManager()) {
            abort(403);
        }

        $request->validate([
            'pengajuan_proyek_id'  => 'required|exists:pengajuan_proyek,id',
            'mahasiswa_id'         => 'required|exists:mahasiswa,id',
            'ls_critical_thinking' => 'required|integer|min:0|max:100',
            'ls_kolaborasi'        => 'required|integer|min:0|max:100',
            'ls_kreativitas'       => 'required|integer|min:0|max:100',
            'ls_komunikasi'        => 'required|integer|min:0|max:100',
            'lf_fleksibilitas'     => 'required|integer|min:0|max:100',
            'lf_kepemimpinan'      => 'required|integer|min:0|max:100',
            'lf_produktivitas'     => 'required|integer|min:0|max:100',
            'lf_social_skill'      => 'required|integer|min:0|max:100',
            'lp_rpp'               => 'required|integer|min:0|max:100',
            'lp_logbook'           => 'required|integer|min:0|max:100',
            'lp_dokumen_projek'    => 'required|integer|min:0|max:100',
        ]);

        $penilaian = PenilaianManager::firstOrNew([
            'mahasiswa_id'        => $request->mahasiswa_id,
            'pengajuan_proyek_id' => $request->pengajuan_proyek_id,
        ]);

        $penilaian->fill([
            'manager_id'           => $user->id,
            'ls_critical_thinking' => $request->ls_critical_thinking,
            'ls_kolaborasi'        => $request->ls_kolaborasi,
            'ls_kreativitas'       => $request->ls_kreativitas,
            'ls_komunikasi'        => $request->ls_komunikasi,
            'lf_fleksibilitas'     => $request->lf_fleksibilitas,
            'lf_kepemimpinan'      => $request->lf_kepemimpinan,
            'lf_produktivitas'     => $request->lf_produktivitas,
            'lf_social_skill'      => $request->lf_social_skill,
            'lp_rpp'               => $request->lp_rpp,
            'lp_logbook'           => $request->lp_logbook,
            'lp_dokumen_projek'    => $request->lp_dokumen_projek,
            'catatan_manager'      => $request->catatan_manager,
        ]);

        // Hitung nilai_manager (55%)
        $penilaian->nilai_manager = $this->hitungNilaiManager($penilaian);
        $penilaian->save();

        return redirect()->route('penilaian.index')
            ->with('success', 'Penilaian Manager berhasil disimpan!');
    }

    // =====================================================================
    // CREATE DOSEN — form input penilaian oleh Dosen Pengampu
    // berdasarkan supervisi_matkul
    // =====================================================================
    public function createDosen()
    {
        $user = auth()->user();

        if (!$user->roleAktifDosen()) {
            abort(403);
        }

        // Ambil semua supervisi matkul yang diampu dosen ini
        $supervisiList = SupervisiMatkul::where('dosen_id', $user->dosen->id)
            ->with(['mahasiswa', 'mataKuliah'])
            ->get();

        return view('penilaian.create_dosen', compact('supervisiList'));
    }

    // =====================================================================
    // STORE DOSEN
    // =====================================================================
    public function storeDosen(Request $request)
    {
        $user = auth()->user();

        if (!$user->roleAktifDosen()) {
            abort(403);
        }

        $request->validate([
            'supervisi_matkul_id' => 'required|exists:supervisi_matkul,id',
            'mahasiswa_id'        => 'required|exists:mahasiswa,id',
            'lit_informasi'       => 'required|integer|min:0|max:100',
            'lit_media'           => 'required|integer|min:0|max:100',
            'lit_teknologi'       => 'required|integer|min:0|max:100',
            'pr_konten'           => 'required|integer|min:0|max:100',
            'pr_visual'           => 'required|integer|min:0|max:100',
            'pr_kosakata'         => 'required|integer|min:0|max:100',
            'pr_tanya_jawab'      => 'required|integer|min:0|max:100',
            'pr_mata_gerak'       => 'required|integer|min:0|max:100',
            'la_penulisan'        => 'required|integer|min:0|max:100',
            'la_pilihan_kata'     => 'required|integer|min:0|max:100',
            'la_konten'           => 'required|integer|min:0|max:100',
        ]);

        // Pastikan supervisi ini memang milik dosen yang login
        $supervisi = SupervisiMatkul::where('id', $request->supervisi_matkul_id)
            ->where('dosen_id', $user->dosen->id)
            ->firstOrFail();

        $penilaian = PenilaianDosen::firstOrNew([
            'mahasiswa_id'        => $request->mahasiswa_id,
            'supervisi_matkul_id' => $request->supervisi_matkul_id,
        ]);

        $penilaian->fill([
            'dosen_id'        => $user->dosen->id,
            'lit_informasi'   => $request->lit_informasi,
            'lit_media'       => $request->lit_media,
            'lit_teknologi'   => $request->lit_teknologi,
            'pr_konten'       => $request->pr_konten,
            'pr_visual'       => $request->pr_visual,
            'pr_kosakata'     => $request->pr_kosakata,
            'pr_tanya_jawab'  => $request->pr_tanya_jawab,
            'pr_mata_gerak'   => $request->pr_mata_gerak,
            'la_penulisan'    => $request->la_penulisan,
            'la_pilihan_kata' => $request->la_pilihan_kata,
            'la_konten'       => $request->la_konten,
            'catatan_dosen'   => $request->catatan_dosen,
        ]);

        // Hitung nilai_dosen (45%)
        $penilaian->nilai_dosen = $this->hitungNilaiDosen($penilaian);
        $penilaian->save();

        return redirect()->route('penilaian.index')
            ->with('success', 'Penilaian Dosen berhasil disimpan!');
    }

    // =====================================================================
    // HELPER — hitung nilai
    // =====================================================================
    private function hitungNilaiManager(PenilaianManager $p): float
    {
        // Learning Skills 20%
        $ls = (($p->ls_critical_thinking + $p->ls_kolaborasi + $p->ls_kreativitas + $p->ls_komunikasi) / 4) * 0.20;
        // Life Skills 20%
        $lf = (($p->lf_fleksibilitas + $p->lf_kepemimpinan + $p->lf_produktivitas + $p->lf_social_skill) / 4) * 0.20;
        // Laporan Proyek 15%
        $lp = (($p->lp_rpp + $p->lp_logbook + $p->lp_dokumen_projek) / 3) * 0.15;

        // Total dari porsi 55%: skala ke 55
        return round(($ls + $lf + $lp) / 0.55 * 0.55, 2);
        // Atau lebih simpel: nilai mentah * 0.55
    }

    private function hitungNilaiDosen(PenilaianDosen $p): float
    {
        // Literacy Skills 15%
        $lit = (($p->lit_informasi + $p->lit_media + $p->lit_teknologi) / 3) * 0.15;
        // Presentasi 15%
        $pr  = (($p->pr_konten + $p->pr_visual + $p->pr_kosakata + $p->pr_tanya_jawab + $p->pr_mata_gerak) / 5) * 0.15;
        // Laporan Akhir 15%
        $la  = (($p->la_penulisan + $p->la_pilihan_kata + $p->la_konten) / 3) * 0.15;

        return round(($lit + $pr + $la) / 0.45 * 0.45, 2);
    }

    // =====================================================================
    // MISC
    // =====================================================================
    public function rubrik() { return view('penilaian.rubrik'); }
    public function bobot()  { return view('penilaian.bobot'); }
}