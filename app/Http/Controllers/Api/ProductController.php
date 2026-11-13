<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $kategori = $request->query('kategori');

        $items = Product::query()
            ->withSum(['orders as terjual' => fn ($o) => $o->where('status', '!=', 'Ditolak')], 'jumlah')
            ->when($q !== '', fn ($x) => $x->where('nama', 'like', "%{$q}%"))
            ->when($kategori, fn ($x) => $x->where('kategori', $kategori))
            ->orderBy('id')
            ->get();

        return ProductResource::collection($items);
    }

    public function show(Product $product)
    {
        $product->loadSum(['orders as terjual' => fn ($o) => $o->where('status', '!=', 'Ditolak')], 'jumlah');

        return new ProductResource($product);
    }

    public function categories()
    {
        return response()->json(
            Product::query()->distinct()->orderBy('kategori')->pluck('kategori')->values()
        );
    }
}
