<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->aktif) {
                Auth::logout();
                return back()->with('error', 'Akun Anda dinonaktifkan oleh pemilik toko. Hubungi pengelola toko.');
            }

            $request->session()->regenerate();
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'nullable|string|max:1000',
        ]);

        // Sesuai PDF: Akun dibuat publik hanya untuk role customer
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'aktif' => true,
        ]);

        // Auto buat profil pelanggan
        Pelanggan::create([
            'user_id' => $user->id,
            'nama_pelanggan' => $validated['name'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat datang di Toko Buah Pak Ridwan.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar (logout).');
    }

    public function profile()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;
        return view('auth.profile', compact('user', 'pelanggan'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        if ($user->isCustomer()) {
            $pelanggan = $user->pelanggan;
            if (!$pelanggan) {
                $pelanggan = new Pelanggan();
                $pelanggan->user_id = $user->id;
            }
            $pelanggan->nama_pelanggan = $validated['name'];
            $pelanggan->no_hp = $validated['no_hp'] ?? '';
            $pelanggan->alamat = $validated['alamat'] ?? null;
            $pelanggan->save();
        }

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKasir()) {
            return redirect()->route('kasir.dashboard');
        } else {
            return redirect()->intended(route('home'));
        }
    }
}
