<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MatkulController extends Controller
{
    public function index()
    {
        // Mengambil semua data mata kuliah, diurutkan berdasarkan semester lalu nama
        $matkuls = MataKuliah::orderBy('semester')->orderBy('nama_matkul')->get();
        
        return view('admin.matkul.index', compact('matkuls'));
    }

    public function store(Request $request)
    {
        // Validasi data yang diinput dari modal
        $validated = $request->validate([
            'kode_matkul'   => 'required|string|max:50|unique:App\Models\MataKuliah,kode_matkul',
            'program_studi' => 'required|string|max:100',
            'nama_matkul'   => 'required|string|max:255',
            'sks'           => 'required|integer|min:1|max:10',
            'semester'      => 'required|integer|min:1|max:8',
        ], [
            'kode_matkul.unique' => 'Kode Mata Kuliah ini sudah terdaftar di sistem.',
        ]);

        // Simpan ke database
        MataKuliah::create($validated);

        return redirect()->route('admin.matkul.index')
                         ->with('success', 'Data Mata Kuliah berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        // Cari dan hapus data
        $matkul = MataKuliah::findOrFail($id);
        $matkul->delete();

        return redirect()->route('admin.matkul.index')
                         ->with('success', 'Data Mata Kuliah berhasil dihapus!');
    }
}