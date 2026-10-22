<?php

namespace App\Http\Controllers;

use App\Support\DummyData;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        return view('home', ['products' => DummyData::products()]);
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Hanya untuk demo frontend; tahap berikutnya memakai Auth + role admin.
        if ($data['email'] === 'admin@lingkarmas.com' && $data['password'] === 'Admin#12345') {
            session(['demo_admin' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function logout()
    {
        session()->forget('demo_admin');
        return redirect()->route('home');
    }
}
