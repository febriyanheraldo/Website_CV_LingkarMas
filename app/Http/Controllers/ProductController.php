<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->q);
        $kat = $request->kategori;

        $items = Product::query()
            ->when($q !== '', fn ($x) => $x->where('nama', 'like', "%{$q}%"))
            ->when($kat, fn ($x) => $x->where('kategori', $kat))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', [
            'items' => $items, 'q' => $q, 'kat' => $kat, 'kategoris' => $this->kategoris(),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product, 'kategoris' => $this->kategoris()]);
    }

    public function store(Request $request)
    {
        // 1. Validasi (dengan pesan bahasa Indonesia)
        $data = $request->validate([
            'kategori'    => 'required|string|max:100',
            'nama'        => 'required|string|max:150',
            'spesifikasi' => 'nullable|string',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // maks 2 MB
            'video' => 'nullable|mimes:mp4,webm|max:20480',   // maks. 20 MB
        ], [
            'required'   => ':attribute wajib diisi.',
            'string'     => ':attribute harus berupa teks.',
            'integer'    => ':attribute harus berupa angka bulat.',
            'min'        => ':attribute tidak boleh kurang dari :min.',
            'max'        => ':attribute terlalu panjang.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, PNG, atau GIF.',
            'foto.max'   => 'Ukuran foto maksimal 2 MB.',
            'video.mimes'    => 'Video harus berformat MP4 atau WEBM.',
            'video.max'      => 'Ukuran video maksimal 20 MB.',
            'video.uploaded' => 'Video gagal diunggah. Ukuran melebihi batas server.',
        ], [
            'kategori' => 'Kategori', 'nama' => 'Nama produk', 'spesifikasi' => 'Spesifikasi',
            'harga' => 'Harga', 'stok' => 'Stok', 'foto' => 'Foto', 'video' => 'Video',
        ]);

        // 2. Pisahkan file foto dari data lainnya
        unset($data['foto'], $data['video']);

        // 3. Simpan file fisik, catat path-nya
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'foto';
            $filename = time() . '_' . $nama . '.' . $file->extension();

            $file->storeAs('produk', $filename, 'public');   // storage/app/public/produk/
            $data['foto'] = 'storage/produk/' . $filename;   // path yang disimpan ke DB
        }

        if ($request->hasFile('video')) {
            $data['video'] = $this->simpanVideo($request->file('video'));
        }

        $product = Product::create($data);

        return redirect()->route('admin.products.show', $product)->with('ok', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', [
            'product' => $product,
            'orders' => $product->orders()->latest()->take(10)->get(),
        ]);
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'kategoris' => $this->kategoris()]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'kategori'    => 'required|string|max:100',
            'nama'        => 'required|string|max:150',
            'spesifikasi' => 'nullable|string',
            'harga'       => 'required|integer|min:0',
            'stok'        => 'required|integer|min:0',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video'       => 'nullable|mimes:mp4,webm|max:20480',
        ], [
            'required'   => ':attribute wajib diisi.',
            'string'     => ':attribute harus berupa teks.',
            'integer'    => ':attribute harus berupa angka bulat.',
            'min'        => ':attribute tidak boleh kurang dari :min.',
            'max'        => ':attribute terlalu panjang.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, PNG, atau GIF.',
            'foto.max'   => 'Ukuran foto maksimal 2 MB.',
            'video.mimes'    => 'Video harus berformat MP4 atau WEBM.',
            'video.max'      => 'Ukuran video maksimal 20 MB.',
            'video.uploaded' => 'Video gagal diunggah. Ukuran melebihi batas server.',
        ], [
            'kategori' => 'Kategori', 'nama' => 'Nama produk', 'spesifikasi' => 'Spesifikasi',
            'harga' => 'Harga', 'stok' => 'Stok', 'foto' => 'Foto', 'video' => 'Video',
        ]);

        unset($data['foto'], $data['video']);

        if ($request->hasFile('foto')) {
            // Urutan: hapus file lama -> simpan file baru -> update record
            $this->deleteFoto($product);

            $file = $request->file('foto');
            $nama = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'foto';
            $filename = time() . '_' . $nama . '.' . $file->extension();

            $file->storeAs('produk', $filename, 'public');
            $data['foto'] = 'storage/produk/' . $filename;
        }

        if ($request->hasFile('video')) {
            $this->deleteVideo($product);
            $data['video'] = $this->simpanVideo($request->file('video'));
        } elseif ($request->boolean('hapus_video')) {
            $this->deleteVideo($product);
            $data['video'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.products.show', $product)->with('ok', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->deleteFoto($product);
        $this->deleteVideo($product);
        $product->delete();   // orders.product_id menjadi NULL, riwayat tetap ada (produk_nama)

        return redirect()->route('admin.products')->with('ok', 'Produk dihapus. Riwayat pesanan tetap tersimpan.');
    }

    private function kategoris()
    {
        return Product::distinct()->orderBy('kategori')->pluck('kategori');
    }

    /** Hanya hapus foto hasil upload (bukan foto bawaan di public/images). */
    private function deleteFoto(Product $product): void
    {
        if ($product->foto && ! str_starts_with($product->foto, 'images/')) {
            Storage::disk('public')->delete($product->foto);
        }
    }

    private function simpanVideo(\Illuminate\Http\UploadedFile $file): string
    {
        $nama = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'video';
        $filename = time() . '_' . $nama . '.' . $file->extension();

        $file->storeAs('video', $filename, 'public');   // storage/app/public/video/

        return 'storage/video/' . $filename;
    }

    private function deleteVideo(Product $product): void
    {
        if ($product->video) {
            Storage::disk('public')->delete('video/' . basename($product->video));
        }
    }
}
