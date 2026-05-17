<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Imports\MahasiswaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('user')->get();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim'   => 'required|unique:mahasiswa,nim',
            'nama'  => 'required|string|max:100',
            'prodi' => 'required|in:mekatronika,otomasi,informatika',
        ]);

        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->nim . '@pbl.com',
            'password' => Hash::make($request->nim),
            'role'     => 'mahasiswa',
            'prodi'    => $request->prodi,
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim'     => $request->nim,
            'nama'    => $request->nama,
        ]);

        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function batchCreate()
    {
        return view('mahasiswa.batch_create');
    }

    public function batchStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'File wajib diupload.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $import = new MahasiswaImport();
        Excel::import($import, $request->file('file'));

        $message = "{$import->inserted} mahasiswa berhasil ditambahkan.";

        if (!empty($import->errors)) {
            return redirect()->route('mahasiswa.index')
                ->with('success', $message)
                ->with('warnings', $import->errors);
        }

        return redirect()->route('mahasiswa.index')->with('success', $message);
    }

    public function downloadTemplate()
{
    $filename = 'template_mahasiswa.csv';

    $callback = function () {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['nim', 'nama', 'prodi']);
        // Tambahkan tab di depan NIM agar Excel baca sebagai teks
        fputcsv($file, ["\t223443001", 'Contoh Nama Mahasiswa', 'mekatronika']);
        fputcsv($file, ["\t223443002", 'Contoh Nama Lainnya',   'otomasi']);
        fclose($file);
    };

    return response()->stream($callback, 200, [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
}

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim'   => 'required|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama'  => 'required|string|max:100',
            'prodi' => 'required|in:mekatronika,otomasi,informatika',
        ]);

        $mahasiswa->update([
            'nim'  => $request->nim,
            'nama' => $request->nama,
        ]);

        $mahasiswa->user->update([
            'name'  => $request->nama,
            'prodi' => $request->prodi,
        ]);

        return redirect('/mahasiswa')->with('success', 'Data mahasiswa berhasil diupdate!');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->laporan()->delete();
        $mahasiswa->logbook()->delete();
        $mahasiswa->logbookHarian()->delete();
        $mahasiswa->penilaianManager()->delete();
        $mahasiswa->penilaianDosen()->delete();
        $mahasiswa->proyek()->detach();

        $user = $mahasiswa->user;
        $mahasiswa->delete();
        $user->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}