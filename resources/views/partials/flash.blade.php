@if (session('ok'))
    <div class="ok">{{ session('ok') }}</div>
@endif
@if ($errors->any())
    <div class="err">
        <ul style="margin:0;padding-left:18px">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
