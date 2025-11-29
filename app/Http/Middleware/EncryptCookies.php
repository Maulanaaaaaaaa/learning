<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class EncryptCookies
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Menyimpan cookies yang dikirim dalam request
        $cookies = $request->cookies->all();

        // Mengenkripsi cookies yang diterima
        foreach ($cookies as $key => $value) {
            if (!in_array($key, $this->except)) {
                // Encrypt cookie jika bukan dalam pengecualian
                Cookie::queue(Cookie::make($key, encrypt($value)));
            }
        }

        // Melanjutkan request
        return $next($request);
    }

    /**
     * Set the cookies that should not be encrypted.
     *
     * @param  array  $except
     * @return void
     */
    public function setExcept(array $except)
    {
        $this->except = $except;
    }
}
