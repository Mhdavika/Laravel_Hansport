<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Cek apakah admin
            if (Auth::user()->email === 'admin@gmail.com') {
                return redirect('/admin/dashboard');
            }

            // Kalau bukan admin, redirect ke homepage biasa
            return redirect('/homepage');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Ini masih aktif tapi hanya akan berhasil jika dijalankan manual
        // Kamu bisa hapus method ini jika ingin menonaktifkan register total
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'user',
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
