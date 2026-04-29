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
        ]);

        $user = User::create([
            'name'     => $request->nama_dosen,
            'email'    => $request->email,
            'password' => Hash::make('password123'),
            'role'     => 'dosen',
        ]);

        Dosen::create([
            'user_id'    => $user->id,
            'nidn'       => $request->nidn,
            'nama_dosen' => $request->nama_dosen,
        ]);

        return redirect('/dosen')->with('success', 'Dosen berhasil ditambahkan!');
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
        ]);

        $dosen->update([
            'nidn'       => $request->nidn,
            'nama_dosen' => $request->nama_dosen,
        ]);
        $dosen->user->update(['name' => $request->nama_dosen]);

        return redirect('/dosen')->with('success', 'Data dosen berhasil diupdate!');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->user->delete();
        return redirect('/dosen')->with('success', 'Dosen berhasil dihapus!');
    }
}