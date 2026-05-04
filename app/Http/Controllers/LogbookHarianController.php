<?php

namespace App\Http\Controllers;

use App\Models\LogbookHarian;
use App\Models\PengajuanProyek;
use Illuminate\Http\Request;

class LogbookHarianController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mhs  = $user->mahasiswa;

        $logbook = LogbookHarian::where('mahasiswa_id', $mhs->id)
            ->with('proyek')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy('minggu_ke');

        return view('logbook_harian.index', compact('logbook'));
    }

    public function create()
    {
        $user = auth()->user();
        $mhs  = $user->mahasiswa;

        $proyek = PengajuanProyek::whereHas('mahasiswa', function ($q) use ($mhs) {
            $q->where('mahasiswa_id', $mhs->id);
        })->where('status', 'approved')->get();

        return view('logbook_harian.create', compact('proyek'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_proyek_id' => 'required|exists:pengajuan_proyek,id',
            'minggu_ke'           => 'required|integer|min:1|max:20',
            'hari'                => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'tanggal'             => 'required|date',
            'aktivitas'           => 'required|string',
            'dokumentasi'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();
        $mhs  = $user->mahasiswa;

        $dokumentasiPath = null;
        if ($request->hasFile('dokumentasi')) {
            $dokumentasiPath = $request->file('dokumentasi')->store('dokumentasi_logbook', 'public');
        }

        LogbookHarian::create([
            'mahasiswa_id'        => $mhs->id,
            'pengajuan_proyek_id' => $request->pengajuan_proyek_id,
            'minggu_ke'           => $request->minggu_ke,
            'hari'                => $request->hari,
            'tanggal'             => $request->tanggal,
            'aktivitas'           => $request->aktivitas,
            'dokumentasi'         => $dokumentasiPath,
        ]);

        return redirect()->route('logbook_harian.index')
            ->with('success', 'Logbook harian berhasil disimpan!');
    }

    public function show(LogbookHarian $logbook_harian)
    {
        return view('logbook_harian.show', compact('logbook_harian'));
    }

    public function edit(LogbookHarian $logbook_harian)
    {
        $mhs = auth()->user()->mahasiswa;

        if ($logbook_harian->mahasiswa_id !== $mhs->id || $logbook_harian->status_verifikasi !== 'pending') {
            abort(403);
        }

        $proyek = PengajuanProyek::whereHas('mahasiswa', function ($q) use ($mhs) {
            $q->where('mahasiswa_id', $mhs->id);
        })->where('status', 'approved')->get();

        return view('logbook_harian.edit', compact('logbook_harian', 'proyek'));
    }

    public function update(Request $request, LogbookHarian $logbook_harian)
    {
        $mhs = auth()->user()->mahasiswa;

        if ($logbook_harian->mahasiswa_id !== $mhs->id || $logbook_harian->status_verifikasi !== 'pending') {
            abort(403);
        }

        $request->validate([
            'minggu_ke'   => 'required|integer|min:1|max:20',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'tanggal'     => 'required|date',
            'aktivitas'   => 'required|string',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $dokumentasiPath = $logbook_harian->dokumentasi;
        if ($request->hasFile('dokumentasi')) {
            if ($dokumentasiPath) {
                \Storage::disk('public')->delete($dokumentasiPath);
            }
            $dokumentasiPath = $request->file('dokumentasi')->store('dokumentasi_logbook', 'public');
        }

        $logbook_harian->update([
            'minggu_ke'   => $request->minggu_ke,
            'hari'        => $request->hari,
            'tanggal'     => $request->tanggal,
            'aktivitas'   => $request->aktivitas,
            'dokumentasi' => $dokumentasiPath,
        ]);

        return redirect()->route('logbook_harian.show', $logbook_harian)
            ->with('success', 'Logbook berhasil diperbarui!');
    }

    public function destroy(LogbookHarian $logbook_harian)
    {
        $mhs = auth()->user()->mahasiswa;

        if ($logbook_harian->mahasiswa_id !== $mhs->id || $logbook_harian->status_verifikasi !== 'pending') {
            abort(403);
        }

        if ($logbook_harian->dokumentasi) {
            \Storage::disk('public')->delete($logbook_harian->dokumentasi);
        }

        $logbook_harian->delete();

        return redirect()->route('logbook_harian.index')
            ->with('success', 'Logbook berhasil dihapus!');
    }

    public function rekapMingguan(Request $request)
    {
        $user     = auth()->user();
        $mhs      = $user->mahasiswa;
        $mingguKe = $request->get('minggu_ke', 1);

        $logbook = LogbookHarian::where('mahasiswa_id', $mhs->id)
            ->where('minggu_ke', $mingguKe)
            ->with('proyek')
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat')")
            ->get();

        $totalMinggu = LogbookHarian::where('mahasiswa_id', $mhs->id)
            ->distinct('minggu_ke')
            ->count('minggu_ke');

        return view('logbook_harian.rekap', compact('logbook', 'mingguKe', 'totalMinggu'));
    }

    public function verifikasi(Request $request, LogbookHarian $logbook_harian)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'catatan_dosen'     => 'nullable|string',
        ]);

        $logbook_harian->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_dosen'     => $request->catatan_dosen,
        ]);

        return back()->with('success', 'Logbook berhasil diverifikasi!');
    }
}