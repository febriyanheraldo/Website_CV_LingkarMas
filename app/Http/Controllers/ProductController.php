<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
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

    public function store(ProductRequest $request)
    {
        $data = $request->safe()->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
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

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->safe()->except('foto');

        if ($request->hasFile('foto')) {
            $this->deleteFoto($product);
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.show', $product)->with('ok', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $this->deleteFoto($product);
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
}
