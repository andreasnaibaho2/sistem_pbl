<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
{
    $kelas = Kelas::with(['mataKuliah', 'dosen', 'manager', 'mahasiswa'])->get();
    return view('kelas.index', compact('kelas'));
}

    public function create()
    {
        $dosen      = Dosen::orderBy('nama_dosen')->get();
        $mataKuliah = MataKuliah::orderBy('nama_matkul')->get();
        return view('kelas.create', compact('dosen','mataKuliah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kelas'     => 'required|unique:kelas,kode_kelas',
            'nama_kelas'     => 'required|string|max:50',
            'matkul_id'      => 'required|exists:mata_kuliah,id',
            'dosen_id'       => 'required|exists:dosen,id',
            'tahun_akademik' => 'required|string|max:10',
        ]);

        Kelas::create([
            'kode_kelas'     => $request->kode_kelas,
            'nama_kelas'     => $request->nama_kelas,
            'matkul_id'      => $request->matkul_id,
            'dosen_id'       => $request->dosen_id,
            'manager_id'     => auth()->id(),
            'tahun_akademik' => $request->tahun_akademik,
        ]);

        return redirect('/kelas')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function show(Kelas $kelas)
    {
        $kelas = Kelas::with(['mataKuliah','dosen','mahasiswa'])->findOrFail($kelas->id);
        $semuaMahasiswa = Mahasiswa::orderBy('nama')->get();
        return view('kelas.show', compact('kelas','semuaMahasiswa'));
    }

    public function edit(Kelas $kelas)
    {
        $dosen      = Dosen::orderBy('nama_dosen')->get();
        $mataKuliah = MataKuliah::orderBy('nama_matkul')->get();
        return view('kelas.edit', compact('kelas','dosen','mataKuliah'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'kode_kelas'     => 'required|unique:kelas,kode_kelas,' . $kelas->id,
            'nama_kelas'     => 'required|string|max:50',
            'matkul_id'      => 'required|exists:mata_kuliah,id',
            'dosen_id'       => 'required|exists:dosen,id',
            'tahun_akademik' => 'required|string|max:10',
        ]);

        $kelas->update($request->only([
            'kode_kelas','nama_kelas','matkul_id','dosen_id','tahun_akademik'
        ]));

        return redirect('/kelas')->with('success', 'Kelas berhasil diupdate!');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect('/kelas')->with('success', 'Kelas berhasil dihapus!');
    }

    public function assignMahasiswa(Request $request, Kelas $kelas)
    {
        $request->validate(['mahasiswa_ids' => 'required|array']);
        $kelas->mahasiswa()->syncWithoutDetaching($request->mahasiswa_ids);
        return back()->with('success', 'Mahasiswa berhasil ditambahkan ke kelas!');
    }

    public function removeMahasiswa(Kelas $kelas, Mahasiswa $mahasiswa)
    {
        $kelas->mahasiswa()->detach($mahasiswa->id);
        return back()->with('success', 'Mahasiswa berhasil dikeluarkan dari kelas!');
    }
}