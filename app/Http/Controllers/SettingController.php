<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'telepon' => Setting::get('telepon'),
            'email' => Setting::get('email'),
            'logo' => Setting::get('logo'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'telepon' => ['required', 'regex:/^[0-9+\-\s()]{6,20}$/'],
            'email' => ['required', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'telepon.regex' => 'Format nomor telepon tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, PNG, atau WEBP.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
        ]);

        Setting::put('telepon', $data['telepon']);
        Setting::put('email', $data['email']);

        $old = Setting::get('logo');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logo', 'public');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            Setting::put('logo', $path);
        } elseif ($request->boolean('hapus_logo') && $old) {
            Storage::disk('public')->delete($old);
            Setting::put('logo', null);
        }

        return back()->with('ok', 'Pengaturan perusahaan berhasil disimpan.');
    }
}
