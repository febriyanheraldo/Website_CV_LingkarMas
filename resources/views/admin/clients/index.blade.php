@extends('layouts.admin')
@section('title', 'Klien')

@section('content')
<h1>Klien</h1>
<p class="mu">Daftar klien dan jumlah pesanannya.</p>

<div class="panel">
    <div class="tw">
        <table>
            <tr><th>Nama</th><th>Email</th><th>HP</th><th>Pesanan</th></tr>
            @foreach ($items as $c)
                <tr><td>{{ $c['nama'] }}</td><td>{{ $c['email'] }}</td><td>{{ $c['hp'] }}</td><td>{{ $c['pesanan'] }}</td></tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
