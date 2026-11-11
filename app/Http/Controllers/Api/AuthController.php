<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'hp'       => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ], [
            'nama.required'     => 'Nama wajib diisi',
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'email.unique'      => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 6 karakter',
        ]);

        User::create($data + ['role' => 'klien']);   // role selalu klien, tidak dari input

        return response()->json(['message' => 'Registrasi berhasil'], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new HttpException(401, 'Email atau password salah');
        }

        if ($user->isAdmin()) {
            throw new HttpException(403, 'Akun admin hanya dapat masuk lewat website');
        }

        return response()->json([
            'token' => $user->createToken('mobile')->plainTextToken,
            'user'  => (new UserResource($user))->resolve(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil keluar']);
    }
}
