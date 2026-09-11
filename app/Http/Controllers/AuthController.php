<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Proses login (mendukung Email atau NISN) dengan Rate Limiting anti-brute force (OWASP A07).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginInput = trim($request->input('email'));
        $throttleKey = Str::transliterate(Str::lower($loginInput) . '|' . $request->ip());

        // Maksimal 5 percobaan gagal dalam 1 menit
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login yang gagal. Akun/IP Anda ditahan sementara demi keamanan. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        $credentials = [
            $isEmail ? 'email' : 'nisn' => $loginInput,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        // Catat kegagalan login dengan waktu tunggu 60 detik
        RateLimiter::hit($throttleKey, 60);
        $attemptsLeft = RateLimiter::remaining($throttleKey, 5);

        return back()->withErrors([
            'email' => $attemptsLeft > 0
                ? "NISN/Email atau password salah. (Sisa percobaan login: {$attemptsLeft})"
                : "NISN/Email atau password salah. Batas percobaan terlampaui, silakan tunggu 60 detik.",
        ])->onlyInput('email');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect berdasarkan role user.
     */
    protected function redirectByRole($user)
    {
        if ($user->isAdmin() || $user->isGuruBk()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('siswa.dashboard');
    }
}
