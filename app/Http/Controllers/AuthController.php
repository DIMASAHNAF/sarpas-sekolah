<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('inventaris.index');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = trim($credentials['login']);
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        // Cek login via email atau username
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$fieldType => $loginInput, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleLabel = $user->role === 'admin' ? 'Administrator' : 'Staf / Pengguna';

            return redirect()->intended(route('inventaris.index'))
                ->with('success', "Selamat datang kembali, {$user->name}! Anda masuk sebagai {$roleLabel}.");
        }

        // Coba alternatif jika login via username tanpa kecocokan email
        if ($fieldType === 'email') {
            if (Auth::attempt(['name' => $loginInput, 'password' => $password], $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('inventaris.index'))
                    ->with('success', "Selamat datang, " . Auth::user()->name . "!");
            }
        }

        return back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors([
                'login' => 'Email/Username atau password yang Anda masukkan salah.',
            ]);
    }

    /**
     * Keluar dari sesi (Logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        $userName = Auth::user()?->name ?? 'Pengguna';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', "Sampai jumpa, {$userName}. Anda telah berhasil keluar dari sistem SIM-SARPRAS.");
    }
}
