import Chart from 'chart.js/auto';

window.Chart = Chart;

// Warna dasar dashboard (selaras dengan tema navy + oranye)
export const palette = {
    primary: '#0b2239',
    accent: '#f97316',
    success: '#16a34a',
    warning: '#d97706',
    danger: '#dc2626',
    info: '#3b82f6',
    muted: '#94a3b8',
    grid: '#eef1f5',
};
window.palette = palette;

Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
Chart.defaults.color = '#475569';

const instances = new Map();

const isObject = (v) => v !== null && typeof v === 'object' && !Array.isArray(v);

function deepMerge(target, source) {
    const out = { ...target };
    for (const key of Object.keys(source)) {
        out[key] = isObject(source[key]) && isObject(target[key])
            ? deepMerge(target[key], source[key])
            : source[key];
    }
    return out;
}

// Cari canvas, hancurkan instance lama (mencegah "Canvas is already in use"), lalu gambar ulang.
window.renderChart = function (id, config) {
    const canvas = document.getElementById(id);
    if (!canvas) return null;

    if (instances.has(id)) {
        instances.get(id).destroy();
    }

    const chart = new Chart(canvas.getContext('2d'), config);
    instances.set(id, chart);
    return chart;
};

// Opsi dasar + override per grafik.
window.chartOptions = function (overrides = {}) {
    const base = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top', align: 'end' } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: palette.grid } },
        },
    };

    const options = deepMerge(base, overrides);

    if (overrides.scales === false) {
        // doughnut tidak punya sumbu x/y
        delete options.scales;
    } else if (isObject(overrides.scales) && 'r' in overrides.scales) {
        // polarArea hanya memakai skala radial (r)
        delete options.scales.x;
        delete options.scales.y;
    }

    return options;
};

// Gradien vertikal untuk area fill pada line chart.
window.chartGradient = function (canvas, from, to) {
    const ctx = canvas.getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height || 260);
    gradient.addColorStop(0, from);
    gradient.addColorStop(1, to);
    return gradient;
};
