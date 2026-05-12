<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        // Blok user yang masih pending
        if ($user->status === 'pending') {
            auth()->logout();
            return redirect('/login')->withErrors([
                'email' => 'Akun Anda belum disetujui Admin.',
            ]);
        }

        $roleEfektif = $user->getRoleEfektif();
        $aksesRole   = $user->akses_role ?? 'keduanya';

        // Cek apakah role yang diminta ada di daftar yang diizinkan
        $diizinkan = false;

        foreach ($roles as $role) {
            // Match langsung role efektif
            if ($roleEfektif === $role) {
                $diizinkan = true;
                break;
            }

            // Match role permanen (admin, mahasiswa)
            if ($user->role === $role) {
                $diizinkan = true;
                break;
            }

            // Dosen: cek akses_role sebelum izinkan
            if ($user->role === 'dosen') {
                if ($role === 'manager_proyek' &&
                    in_array($aksesRole, ['manager_proyek', 'keduanya']) &&
                    $roleEfektif === 'manager_proyek') {
                    $diizinkan = true;
                    break;
                }
                if ($role === 'dosen' &&
                    in_array($aksesRole, ['dosen_pengampu', 'keduanya']) &&
                    $roleEfektif === 'dosen_pengampu') {
                    $diizinkan = true;
                    break;
                }
            }
        }

        if (!$diizinkan) {
            // Kalau dosen, arahkan ke dashboard dengan pesan
            if ($user->role === 'dosen') {
                return redirect()->route('dashboard')
                    ->with('error', 'Akses ditolak. Role Anda saat ini tidak memiliki izin.');
            }
            abort(403);
        }

        return $next($request);
    }
}