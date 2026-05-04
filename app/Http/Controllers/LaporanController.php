<?php

namespace App\Http\Controllers;

use App\Models\LaporanMkPpi;
use App\Models\PengajuanProyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isMahasiswa()) {
            $laporan = LaporanMkPpi::with('proyek')
                ->where('mahasiswa_id', $user->mahasiswa->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->isDosen()) {
            $laporan = LaporanMkPpi::with(['mahasiswa', 'proyek'])
                ->whereHas('proyek', function($q) use ($user) {
                    $q->where('dosen_pengampu_id', $user->dosen->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $laporan = LaporanMkPpi::with(['mahasiswa', 'proyek'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->isMahasiswa()) {
            $proyekList = PengajuanProyek::whereHas('mahasiswa', function($q) use ($user) {
                $q->where('mahasiswa_id', $user->mahasiswa->id);
            })->where('status', 'approved')->get();
        } else {
            $proyekList = PengajuanProyek::where('status', 'approved')->get();
        }

        return view('laporan.create', compact('proyekList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_proyek_id' => 'required|exists:pengajuan_proyek,id',
            'jenis_laporan'       => 'required|in:Supervisi,Laporan Teknik,PAB',
            'file'                => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $user     = auth()->user();
        $file     = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('laporan', $fileName, 'public');
        $kode     = 'LPR-' . $user->mahasiswa->nim . '-' . now()->format('YmdHis');

        LaporanMkPpi::create([
            'kode_laporan'        => $kode,
            'mahasiswa_id'        => $user->mahasiswa->id,
            'pengajuan_proyek_id' => $request->pengajuan_proyek_id,
            'jenis_laporan'       => $request->jenis_laporan,
            'file_path'           => $filePath,
        ]);

        return redirect('/laporan')->with('success', 'Laporan berhasil diupload!');
    }

    public function show(LaporanMkPpi $laporan)
    {
        return view('laporan.show', compact('laporan'));
    }

    public function verifikasi(Request $request, LaporanMkPpi $laporan)
    {
        $request->validate([
            'status_verifikasi'  => 'required|in:disetujui,ditolak',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $laporan->update([
            'status_verifikasi'  => $request->status_verifikasi,
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        return back()->with('success', 'Laporan berhasil diverifikasi!');
    }
}