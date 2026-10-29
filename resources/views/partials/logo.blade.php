@php
    $dark = $dark ?? false;
    $showText = $showText ?? true;
    $name = $text ?? 'CV. Lingkar Mas';
    $uploaded = \App\Models\Setting::get('logo');
    $src = null;
    $chip = false;

    if ($uploaded && \Illuminate\Support\Facades\Storage::disk('public')->exists($uploaded)) {
        $src = 'storage/' . $uploaded;
        $chip = $dark;
    } elseif ($dark && file_exists(public_path('images/logo-putih.png'))) {
        $src = 'images/logo-putih.png';
    } elseif (file_exists(public_path('images/logo.png'))) {
        $src = 'images/logo.png';
        $chip = $dark;
    }
@endphp

<a class="logo" href="{{ route('home') }}">
    @if ($src)
        <img class="logo-img {{ $chip ? 'chip' : '' }}" src="{{ asset($src) }}" alt="{{ $showText ? '' : $name }}">
    @else
        <i>⚙</i>
    @endif

    @if ($showText || ! $src)
        <span class="logo-text">{{ $name }}</span>
    @endif
</a>
