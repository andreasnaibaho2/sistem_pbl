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

        $roleEfektif = $user->getRoleEfektif();

        if (in_array($roleEfektif, $roles) || in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
}