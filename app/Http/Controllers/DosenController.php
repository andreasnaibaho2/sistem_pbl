<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::with('user')->get();
        return view('dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nidn'       => 'required|unique:dosen,nidn',
            'nama_dosen' => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'akses_role' => 'required|in:dosen_pengampu,manager_proyek,keduanya',
        ]);

        $aksesRole = $request->akses_role;
        $roleAktif = match($aksesRole) {
            'manager_proyek' => 'manager_proyek',
            'keduanya'       => 'dosen_pengampu',
            default          => 'dosen_pengampu',
        };

        $user = User::create([
            'name'       => $request->nama_dosen,
            'email'      => $request->email,
            'password'   => Hash::make('password123'),
            'role'       => 'dosen',
            'role_aktif' => $roleAktif,
            'akses_role' => $aksesRole,
            'status'     => 'active',
        ]);

        Dosen::create([
            'user_id'    => $user->id,
            'nidn'       => $request->nidn,
            'nama_dosen' => $request->nama_dosen,
        ]);

        return redirect('/dosen')->with('success', 'Dosen ' . $request->nama_dosen . ' berhasil ditambahkan!');
    }

    public function edit(Dosen $dosen)
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nidn'       => 'required|unique:dosen,nidn,' . $dosen->id,
            'nama_dosen' => 'required|string|max:100',
            'akses_role' => 'required|in:dosen_pengampu,manager_proyek,keduanya',
        ]);

        $aksesRole = $request->akses_role;

        // Jika keduanya, pertahankan role_aktif yang sedang berjalan
        // Jika 1 akses, paksa role_aktif sesuai akses baru
        $roleAktifSekarang = $dosen->user->role_aktif ?? 'dosen_pengampu';
        $roleAktifBaru = match($aksesRole) {
            'dosen_pengampu' => 'dosen_pengampu',
            'manager_proyek' => 'manager_proyek',
            default          => $roleAktifSekarang,
        };

        $dosen->update([
            'nidn'       => $request->nidn,
            'nama_dosen' => $request->nama_dosen,
        ]);

        $dosen->user->update([
            'name'       => $request->nama_dosen,
            'akses_role' => $aksesRole,
            'role_aktif' => $roleAktifBaru,
        ]);

        return redirect('/dosen')->with('success', 'Data dosen ' . $request->nama_dosen . ' berhasil diupdate!');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->user->delete();
        return redirect('/dosen')->with('success', 'Dosen berhasil dihapus!');
    }
}