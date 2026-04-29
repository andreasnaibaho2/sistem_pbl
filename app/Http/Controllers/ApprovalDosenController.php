<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ApprovalDosenController extends Controller
{
    public function index()
    {
        $pendingDosen = User::where('role', 'dosen')
            ->where('status', 'pending')
            ->with('dosen')
            ->latest()
            ->get();

        $approvedDosen = User::where('role', 'dosen')
            ->where('status', 'active')
            ->with('dosen')
            ->latest()
            ->get();

        return view('admin.approval_dosen', compact('pendingDosen', 'approvedDosen'));
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'Akun dosen ' . $user->name . ' berhasil disetujui.');
    }

    public function reject(User $user)
    {
        // Hapus record dosen & user
        $user->dosen()->delete();
        $user->delete();
        return back()->with('success', 'Akun dosen berhasil ditolak dan dihapus.');
    }
}