@php
    $viteSiap = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
@endphp

@if ($viteSiap)
    @vite('resources/js/charts.js')
@else
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.chart-box').forEach(function (kotak) {
                kotak.innerHTML = '<p class="chart-empty">Grafik belum aktif. Jalankan <b>npm install</b> lalu <b>npm run build</b>.</p>';
            });
        });
    </script>
@endif
