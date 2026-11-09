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
            'telepon'     => Setting::get('telepon'),
            'email'       => Setting::get('email'),
            'logo'        => Setting::get('logo'),
            'ttd_nama'    => Setting::get('ttd_nama'),
            'ttd_jabatan' => Setting::get('ttd_jabatan'),
            'ttd'         => Setting::get('ttd'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'telepon'     => ['required', 'regex:/^[0-9+\-\s()]{6,20}$/'],
            'email'       => ['required', 'email', 'max:255'],
            'logo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'ttd_nama'    => ['nullable', 'string', 'max:100'],
            'ttd_jabatan' => ['nullable', 'string', 'max:100'],
            'ttd'         => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ], [
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'telepon.regex'    => 'Format nomor telepon tidak valid.',
            'email.required'   => 'Email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'logo.image'       => 'Logo harus berupa gambar.',
            'logo.mimes'       => 'Logo harus berformat JPG, PNG, atau WEBP.',
            'logo.max'         => 'Ukuran logo maksimal 2 MB.',
            'ttd.image'        => 'Tanda tangan harus berupa gambar.',
            'ttd.mimes'        => 'Tanda tangan harus berformat PNG atau JPG.',
            'ttd.max'          => 'Ukuran tanda tangan maksimal 2 MB.',
            'ttd_nama.max'     => 'Nama penandatangan terlalu panjang.',
            'ttd_jabatan.max'  => 'Jabatan terlalu panjang.',
        ]);

        Setting::put('telepon', $data['telepon']);
        Setting::put('email', $data['email']);
        Setting::put('ttd_nama', $data['ttd_nama'] ?? null);
        Setting::put('ttd_jabatan', $data['ttd_jabatan'] ?? null);

        // Logo (disk publik, tampil di website)
        $oldLogo = Setting::get('logo');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logo', 'public');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::put('logo', $path);
        } elseif ($request->boolean('hapus_logo') && $oldLogo) {
            Storage::disk('public')->delete($oldLogo);
            Setting::put('logo', null);
        }

        // Tanda tangan (disk privat, hanya dibaca server untuk PDF)
        $oldTtd = Setting::get('ttd');

        if ($request->hasFile('ttd')) {
            $path = $request->file('ttd')->store('ttd', 'local');
            if ($oldTtd) {
                Storage::disk('local')->delete($oldTtd);
            }
            Setting::put('ttd', $path);
        } elseif ($request->boolean('hapus_ttd') && $oldTtd) {
            Storage::disk('local')->delete($oldTtd);
            Setting::put('ttd', null);
        }

        return back()->with('ok', 'Pengaturan perusahaan berhasil disimpan.');
    }
}
