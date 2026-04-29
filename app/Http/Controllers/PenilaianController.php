<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Kelas;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isMahasiswa()) {
            $penilaian = Penilaian::with(['kelas', 'dosen'])
                ->where('mahasiswa_id', $user->mahasiswa->id)
                ->get();
        } elseif ($user->isManager()) {
            $penilaian = Penilaian::with(['mahasiswa', 'kelas'])
                ->whereHas('kelas', fn($q) => $q->where('manager_id', $user->id))
                ->get();
        } elseif ($user->isDosen()) {
            $penilaian = Penilaian::with(['mahasiswa', 'kelas'])
                ->whereHas('kelas', fn($q) => $q->where('dosen_id', $user->dosen->id))
                ->get();
        } else {
            // Admin
            $penilaian = Penilaian::with(['mahasiswa', 'kelas', 'dosen'])->get();
        }

        return view('penilaian.index', compact('penilaian'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->isManager()) {
            $kelas = Kelas::where('manager_id', $user->id)
                ->with(['mahasiswa' => fn($q) => $q->orderBy('nama')])
                ->get();
        } elseif ($user->isDosen()) {
            $kelas = Kelas::where('dosen_id', $user->dosen->id)
                ->with(['mahasiswa' => fn($q) => $q->orderBy('nama')])
                ->get();
        } else {
            $kelas = collect();
        }

        return view('penilaian.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'kelas_id'     => 'required|exists:kelas,id',
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
        ]);

        // Ambil atau buat record penilaian
        $penilaian = Penilaian::firstOrNew([
            'kelas_id'     => $request->kelas_id,
            'mahasiswa_id' => $request->mahasiswa_id,
        ]);

        if ($user->isManager()) {
            $request->validate([
                'ls_critical_thinking' => 'required|numeric|min:0|max:100',
                'ls_kolaborasi'        => 'required|numeric|min:0|max:100',
                'ls_kreativitas'       => 'required|numeric|min:0|max:100',
                'ls_komunikasi'        => 'required|numeric|min:0|max:100',
                'lf_fleksibilitas'     => 'required|numeric|min:0|max:100',
                'lf_kepemimpinan'      => 'required|numeric|min:0|max:100',
                'lf_produktivitas'     => 'required|numeric|min:0|max:100',
                'lf_social_skill'      => 'required|numeric|min:0|max:100',
                'lp_rpp'               => 'required|numeric|min:0|max:100',
                'lp_logbook'           => 'required|numeric|min:0|max:100',
                'lp_dokumen_projek'    => 'required|numeric|min:0|max:100',
            ]);

            $penilaian->fill([
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

            $penilaian->nilai_manager = $penilaian->hitungNilaiManager();

        } elseif ($user->isDosen()) {
            $request->validate([
                'lit_informasi'   => 'required|numeric|min:0|max:100',
                'lit_media'       => 'required|numeric|min:0|max:100',
                'lit_teknologi'   => 'required|numeric|min:0|max:100',
                'pr_konten'       => 'required|numeric|min:0|max:100',
                'pr_visual'       => 'required|numeric|min:0|max:100',
                'pr_kosakata'     => 'required|numeric|min:0|max:100',
                'pr_tanya_jawab'  => 'required|numeric|min:0|max:100',
                'pr_mata_gerak'   => 'required|numeric|min:0|max:100',
                'la_penulisan'    => 'required|numeric|min:0|max:100',
                'la_pilihan_kata' => 'required|numeric|min:0|max:100',
                'la_konten'       => 'required|numeric|min:0|max:100',
            ]);

            $penilaian->fill([
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
                'dosen_id'        => $user->dosen->id,
            ]);

            $penilaian->nilai_dosen = $penilaian->hitungNilaiDosen();
        }

        // Hitung nilai akhir gabungan
        $penilaian->nilai_akhir = $penilaian->hitungNilaiAkhir();
        $penilaian->save();

        return redirect()->route('penilaian.index')
            ->with('success', 'Nilai berhasil disimpan!');
    }

    public function show(Penilaian $penilaian)
    {
        $penilaian->load(['mahasiswa', 'kelas', 'dosen']);
        return view('penilaian.show', compact('penilaian'));
    }
    public function rubrik()
    {
    return view('penilaian.rubrik');
    }
    public function bobot()
{
    return view('penilaian.bobot');
}
}