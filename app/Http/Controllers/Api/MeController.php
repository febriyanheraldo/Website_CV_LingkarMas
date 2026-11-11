<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\OrderService;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'hp'   => 'nullable|string|max:20',
        ], [
            'nama.required' => 'Nama wajib diisi',
        ]);

        $user = $request->user();
        $user->update($data);

        return new UserResource($user->fresh());
    }

    public function destroy(Request $request, OrderService $service)
    {
        $service->deleteAccount($request->user());

        return response()->json(['message' => 'Akun berhasil dihapus']);
    }
}
