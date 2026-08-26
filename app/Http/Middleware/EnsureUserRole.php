<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Membatasi akses fitur berdasarkan jenis akun (Supply/Demand) - NFR keamanan.
// Pakai di route: ->middleware("role:supply") atau ->middleware("role:demand")
class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, "Anda tidak memiliki akses ke halaman ini.");
        }

        return $next($request);
    }
}