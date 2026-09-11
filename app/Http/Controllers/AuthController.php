<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Proses login (mendukung Email atau NISN).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginInput = trim($request->input('email'));
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        $credentials = [
            $isEmail ? 'email' : 'nisn' => $loginInput,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'NISN/Email atau password salah.',
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
