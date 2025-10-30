<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckJabatan
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Jika user belum login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Daftar jabatan yang TIDAK boleh masuk ke halaman home
        $blockedJabatan = [
            'Direktur',
            'Wdir Umumu dan Keuangan',
            'Wadir Pelayanan',
        ];

        if (in_array($user->jabatan, $blockedJabatan)) {
            return redirect()->route('dashboard.direktur');
        }

        return $next($request);
    }
}
