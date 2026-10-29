<?php

namespace App\Http\Controllers;

use App\Support\DummyData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function home()
    {
        return view('home', ['products' => \App\Models\Product::orderBy('id')->take(8)->get()]);
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->only('email'));
        }

        // hanya role admin yang boleh masuk panel web
        if (! Auth::user()->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Akun ini bukan admin. Silakan gunakan aplikasi mobile.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
