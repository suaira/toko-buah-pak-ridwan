<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (!$user->aktif) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh pemilik toko.');
        }

        if (!empty($roles) && !in_array($user->role, $roles)) {
            // Redirect based on actual role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            } elseif ($user->role === 'kasir') {
                return redirect()->route('kasir.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            } else {
                return redirect()->route('home')->with('error', 'Akses ditolak.');
            }
        }

        return $next($request);
    }
}
