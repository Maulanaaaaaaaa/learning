<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Jika pengguna belum login atau role-nya tidak sesuai, redirect ke halaman home
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect()->route('home')->with([
                'notifikasi' => 'Anda tidak memiliki akses. Silakan login terlebih dahulu!',
                'type' => 'warning'
            ]);
        }

        // Jika role sesuai, lanjutkan permintaan
        return $next($request);
    }
}
