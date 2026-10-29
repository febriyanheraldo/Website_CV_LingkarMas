@extends('layouts.admin')
@section('title', 'Klien')

@section('content')
@include('partials.back', ['url' => route('admin.dashboard'), 'label' => 'Kembali ke dashboard'])
<h1>Klien</h1>
<p class="mu">Daftar klien dan jumlah pesanannya.</p>

<div class="panel">
    <div class="tw">
        <table>
            <tr><th>Nama</th><th>Email</th><th>HP</th><th>Pesanan</th><th></th></tr>
            @forelse ($items as $c)
                <tr>
                    <td>{{ $c->nama }}</td><td>{{ $c->email }}</td><td>{{ $c->hp }}</td><td>{{ $c->orders_count }}</td>
                    <td><a class="btn sm line" href="{{ route('admin.clients.show', $c) }}">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada klien.</td></tr>
            @endforelse
        </table>
    </div>
    {{ $items->links('partials.pagination') }}
</div>
@endsection
