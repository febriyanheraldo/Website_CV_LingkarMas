@extends('layouts.admin')
@section('title', 'Pesanan')

@section('content')
@include('partials.back', ['url' => route('admin.dashboard'), 'label' => 'Kembali ke dashboard'])
@if (session('import_log'))
    <div class="warn">
        <b>Baris yang dilewati:</b>
        <ul>
            @foreach (session('import_log') as $baris)
                <li>{{ $baris }}</li>
            @endforeach
        </ul>
    </div>
@endif
<h1>Pesanan</h1>
<p class="mu">Ubah status sesuai alur: Menunggu, Diproses, Dikirim.</p>

<form class="tools" method="GET">
    <input name="q" value="{{ $q }}" placeholder="Cari kode atau nama klien" aria-label="Cari">
    <select name="status" aria-label="Status" onchange="this.form.submit()">
        <option value="">Semua status</option>
        @foreach (\App\Enums\OrderStatus::cases() as $s)
            <option value="{{ $s->value }}" @selected($status === $s->value)>{{ $s->value }}</option>
        @endforeach
    </select>
    <button class="btn sm" type="submit">Cari</button>
    <a class="btn sm excel" href="{{ route('admin.laporan.pesanan.excel', request()->only(['q', 'status'])) }}">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13l4 5M13 13l-4 5"/></svg>
        Export Excel
    </a>
    <button type="button" class="btn sm imp" onclick="document.getElementById('importModal').showModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
        Import Excel
    </button>
</form>

<div class="panel">
    <div class="tw">
        <table>
            <tr><th>Kode</th><th>Tanggal</th><th>Klien</th><th>Produk</th><th>Jml</th><th>Total</th><th>Status</th><th></th></tr>
            @forelse ($items as $o)
                <tr>
                    <td>{{ $o->kode }}</td>
                    <td>{{ $o->tanggal }}</td>
                    <td>{{ $o->user?->nama ?? 'Akun dihapus' }}</td>
                    <td>{{ $o->produk_nama }}</td>
                    <td>{{ $o->jumlah }}</td>
                    <td>{{ $o->total_rp }}</td>
                    <td><span class="st {{ $o->status->value }}">{{ $o->status->value }}</span></td>
                    <td><a class="btn sm line" href="{{ route('admin.orders.show', $o) }}">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="8">Pesanan tidak ditemukan.</td></tr>
            @endforelse
        </table>
    </div>
    {{ $items->links('partials.pagination') }}
</div>

<dialog id="importModal" class="modal">
    <form method="POST" action="{{ route('admin.orders.import') }}" enctype="multipart/form-data">
        @csrf
        <h3>Import pesanan dari Excel</h3>
        <ol>
            <li>Unduh template, lalu hapus baris contohnya.</li>
            <li>Isi kolom: Email Klien, Produk (nama persis), Jumlah, Alamat.</li>
            <li>Unggah file di bawah ini. Status pesanan otomatis <b>Menunggu</b> dan stok dikurangi.</li>
        </ol>

        <label for="file">File (.xlsx, .xls, .csv, maks. 2 MB)</label>
        <input id="file" type="file" name="file" accept=".xlsx,.xls,.csv" required>
        @error('file') <p class="field-err">{{ $message }}</p> @enderror

        <div class="row" style="margin-top:16px;align-items:center">
            <button class="btn" type="submit">Import</button>
            <button class="btn line" type="button" onclick="this.closest('dialog').close()">Batal</button>
            <a href="{{ route('admin.orders.template') }}" style="font-size:13px;text-decoration:underline">Download template</a>
        </div>
    </form>
</dialog>

@if ($errors->has('file'))
    <script>document.getElementById('importModal').showModal();</script>
@endif
@endsection
