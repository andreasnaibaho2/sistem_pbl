<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'nim'  => 'required|unique:mahasiswa,nim',
            'nama' => 'required|string|max:100',
        ]);

        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->nim . '@pbl.com',
            'password' => Hash::make($request->nim),
            'role'     => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim'     => $request->nim,
            'nama'    => $request->nama,
        ]);

        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan!');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim'  => 'required|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama' => 'required|string|max:100',
        ]);

        $mahasiswa->update([
            'nim'  => $request->nim,
            'nama' => $request->nama,
        ]);

        $mahasiswa->user->update(['name' => $request->nama]);

        return redirect('/mahasiswa')->with('success', 'Data mahasiswa berhasil diupdate!');
    }

    public function destroy(Mahasiswa $mahasiswa)
{
    $mahasiswa->laporan()->delete();
    $mahasiswa->logbook()->delete();
    $mahasiswa->penilaian()->delete();
    $mahasiswa->kelas()->detach();

    $user = $mahasiswa->user;
    $mahasiswa->delete();
    $user->delete();

    return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
}
}