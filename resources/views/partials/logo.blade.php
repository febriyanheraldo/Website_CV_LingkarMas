@php
    $dark = $dark ?? false;
    $white = public_path('images/logo-putih.png');
    $normal = public_path('images/logo/logo_lingkarmas.png');

    if ($dark && file_exists($white)) {
        $src = 'images/logo-putih.png';
        $chip = false;
    } elseif (file_exists($normal)) {
        $src = 'images/logo/logo_lingkarmas.png';
        $chip = $dark;
    } else {
        $src = null;
    }
@endphp

<a class="logo" href="{{ route('home') }}">
    @if ($src)
        <img class="logo-img {{ $chip ? 'chip' : '' }}" src="{{ asset($src) }}" alt="{{ $text ?? 'CV. Lingkar Mas' }}">
    @else
        <i>⚙</i>{{ $text ?? 'CV. Lingkar Mas' }}
    @endif
</a>
