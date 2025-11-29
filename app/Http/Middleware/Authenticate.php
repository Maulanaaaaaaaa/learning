<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna sudah terautentikasi
        if (Auth::guest()) {
            // Jika pengguna belum login, arahkan ke halaman login
            return redirect()->route('login');
        }

        // Jika pengguna sudah terautentikasi, lanjutkan permintaan
        return $next($request);
    }
}
