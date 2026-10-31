// Dashboard Charts - Extracted from card.blade.php
// Load via: <script src="{{ asset('js/dashboard/charts.js') }}"></script>

// ============ DATA INJECTION ============
// These will be populated by blade template via window.dashboardData
window.dashboardData = window.dashboardData || {};

// ============ CHART INSTANCES ============
let visitorChart = null;
let deviceChart = null;
let agendaStatusChart = null;

// ============ UTILITIES ============
function getCanvas(id) {
    const el = document.getElementById(id);
    return el ? el.getContext('2d') : null;
}

function destroyChart(chart) {
    if (chart) {
        chart.destroy();
        return null;
    }
    return chart;
}

// ============ VISITOR CHART ============
function createVisitorChart(type = 'daily') {
    const data = window.dashboardData.visitorStats;
    if (!data) return;

    const labels = type === 'daily' ? data.daily.labels : data.monthly.labels;
    const totalData = type === 'daily' ? data.daily.total : data.monthly.total;
    const uniqueData = type === 'daily' ? data.daily.unique : data.monthly.unique;

    visitorChart = destroyChart(visitorChart);

    const ctx = getCanvas('visitorChart');
    if (!ctx) return;

    visitorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Kunjungan',
                data: totalData,
                borderColor: '#5e72e4',
                backgroundColor: 'rgba(94, 114, 228, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6,
            }, {
                label: 'Pengunjung Unik',
                data: uniqueData,
                borderColor: '#2dce89',
                backgroundColor: 'rgba(45, 206, 137, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: getLineChartOptions()
    });
}

function switchVisitorChart(type, btn) {
    document.querySelectorAll('[data-chart-type]').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    createVisitorChart(type);
}

// ============ DEVICE CHART ============
async function createDeviceChart() {
    try {
        const base = window.dashboardData.routes?.deviceStats || '/backend/dashboard/device-stats';
        const params = new URLSearchParams();
        const startInput = document.querySelector('input[name="start_date"]');
        const endInput = document.querySelector('input[name="end_date"]');
        if (startInput && startInput.value) params.set('start_date', startInput.value);
        if (endInput && endInput.value) params.set('end_date', endInput.value);
        const url = params.toString() ? base + '?' + params.toString() : base;
        const response = await fetch(url);
        const data = await response.json();

        deviceChart = destroyChart(deviceChart);

        const ctx = getCanvas('deviceChart');
        if (!ctx) return;

        deviceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: ['#5e72e4', '#2dce89', '#fb6340', '#f5365c'],
                    borderWidth: 0,
                    hoverOffset: 10,
                }]
            },
            options: getDoughnutOptions('bottom', 11)
        });
    } catch (err) {
        console.error('Failed to load device stats:', err);
    }
}

// ============ AGENDA STATUS CHART ============
function createAgendaStatusChart() {
    const data = window.dashboardData.agendas;
    if (!data) return;

    agendaStatusChart = destroyChart(agendaStatusChart);

    const ctx = getCanvas('agendaStatusChart');
    if (!ctx) return;

    agendaStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Dipublikasikan', 'Draft'],
            datasets: [{
                data: [data.published, data.total - data.published],
                backgroundColor: ['#2dce89', '#fb6340'],
                borderWidth: 0,
                hoverOffset: 10,
            }]
        },
        options: getDoughnutOptions(false, 11, false)
    });
}


// ============ AGENDA TREN CHART (OPD) ============
let agendaTrenChart = null;
function createAgendaTrenChart() {
    const labels = window.dashboardData.agendaTrenLabels;
    const data = window.dashboardData.agendaTrenData;
    const pending = window.dashboardData.agendaPendingData;
    if (!labels || !data) return;

    agendaTrenChart = destroyChart(agendaTrenChart);

    const ctx = getCanvas('agendaTrenChart');
    if (!ctx) return;

    const datasets = [{
        label: 'Agenda Masuk',
        data: data,
        borderColor: '#0a4d8e',
        backgroundColor: 'rgba(10, 77, 142, 0.12)',
        fill: true,
        tension: 0.4,
        pointRadius: 4,
    }];
    if (pending) {
        datasets.push({
            label: 'Pending',
            data: pending,
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.15)',
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            borderDash: [5, 4],
        });
    }

    agendaTrenChart = new Chart(ctx, {
        type: 'line',
        data: { labels: labels, datasets: datasets },
        options: getLineChartOptions(true, true)
    });
}

// ============ TOP OPD CHART ============
let topOpdChart = null;
function createTopOpdChart() {
    const labels = window.dashboardData.topOpdLabels;
    const data = window.dashboardData.topOpdData;
    if (!labels || !data) return;

    topOpdChart = destroyChart(topOpdChart);

    const ctx = getCanvas('topOpdChart');
    if (!ctx) return;

    topOpdChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Agenda',
                data: data,
                backgroundColor: ['#0a4d8e', '#139a8d', '#5e72e4', '#825ee4', '#11cdef'],
                borderRadius: 6,
            }]
        },
        options: {
            ...getBarChartOptions(),
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                y: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });
}

// ============ BROWSER CHART ============
let browserChart = null;
function createBrowserChart() {
    const labels = window.dashboardData.browserLabels;
    const data = window.dashboardData.browserData;
    if (!labels || !data) return;

    browserChart = destroyChart(browserChart);

    const ctx = getCanvas('browserChart');
    if (!ctx) return;

    browserChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['#5e72e4', '#11cdef', '#fb6340', '#2dce89', '#f5365c'],
                borderWidth: 0,
                hoverOffset: 10,
            }]
        },
        options: getDoughnutOptions('bottom', 11)
    });
}

// ============ KOTA CHART ============
let kotaChart = null;
function createKotaChart() {
    const labels = window.dashboardData.kotaLabels;
    const data = window.dashboardData.kotaData;
    if (!labels || !data) return;

    kotaChart = destroyChart(kotaChart);

    const ctx = getCanvas('kotaChart');
    if (!ctx) return;

    kotaChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Kunjungan',
                data: data,
                backgroundColor: ['#0a4d8e', '#139a8d', '#5e72e4', '#11cdef', '#fb6340'],
                borderRadius: 6,
            }]
        },
        options: getBarChartOptions()
    });
}

// ============ AGENDA KATEGORI CHART ============
let agendaKategoriChart = null;
function createAgendaKategoriChart() {
    const labels = window.dashboardData.agendaKategoriLabels;
    const data = window.dashboardData.agendaKategoriData;
    if (!labels || !data) return;

    agendaKategoriChart = destroyChart(agendaKategoriChart);

    const ctx = getCanvas('agendaKategoriChart');
    if (!ctx) return;

    agendaKategoriChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Agenda',
                data: data,
                backgroundColor: ['#11cdef', '#fb6340', '#825ee4', '#2dce89', '#f5365c', '#ffd600'],
                borderRadius: 6,
            }]
        },
        options: getBarChartOptions()
    });
}

// ============ TREN KATEGORI CHART ============
let trendKategoriChart = null;
function createTrendKategoriChart() {
    const labels = window.dashboardData.trendKategoriLabels;
    const nama = window.dashboardData.trendKategoriNama;
    const data = window.dashboardData.trendKategoriData;
    if (!labels || !nama || !data || !data.length) return;

    trendKategoriChart = destroyChart(trendKategoriChart);

    const ctx = getCanvas('trendKategoriChart');
    if (!ctx) return;

    const palette = ['#5e72e4', '#11cdef', '#fb6340', '#2dce89', '#f5365c', '#ffd600', '#825ee4', '#172b4d'];
    const datasets = nama.map(function (name, idx) {
        return {
            label: name,
            data: data.map(function (bulan) { return bulan[idx] || 0; }),
            borderColor: palette[idx % palette.length],
            backgroundColor: palette[idx % palette.length] + '22',
            tension: 0.35,
            fill: false,
            borderWidth: 2,
            pointRadius: 3,
        };
    });

    trendKategoriChart = new Chart(ctx, {
        type: 'line',
        data: { labels: labels, datasets: datasets },
        options: getLineChartOptions(true, true)
    });
}

// ============ KOMPOSISI KONTEN CHART ============
let komposisiKontenChart = null;
function createKomposisiKontenChart() {
    const labels = window.dashboardData.komposisiKontenLabels;
    const data = window.dashboardData.komposisiKontenData;
    if (!labels || !data) return;

    komposisiKontenChart = destroyChart(komposisiKontenChart);

    const ctx = getCanvas('komposisiKontenChart');
    if (!ctx) return;

    komposisiKontenChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['#5e72e4', '#11cdef', '#fb6340', '#2dce89'],
                borderWidth: 0,
                hoverOffset: 10,
            }]
        },
        options: getDoughnutOptions('bottom', 11)
    });
}

// ============ SHARED OPTIONS ============
function getLineChartOptions(showLegend = true, beginAtZero = true) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: {
                position: 'top',
                display: showLegend,
                labels: { usePointStyle: true, padding: 20, font: { size: 12 } }
            },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#344767',
                bodyColor: '#67748e',
                borderColor: '#e9ecef',
                borderWidth: 1,
                padding: 12,
                displayColors: true,
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.raw.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: beginAtZero,
                grid: { color: '#f1f5f9' },
                ticks: { callback: v => v.toLocaleString() }
            },
            x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8, font: { size: 11 } } }
        }
    };
}

function getDoughnutOptions(legendPosition = 'bottom', fontSize = 11, showLegend = true) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                position: legendPosition,
                display: showLegend,
                labels: { usePointStyle: true, padding: 15, font: { size: fontSize } }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const pct = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                        return context.label + ': ' + context.raw.toLocaleString() + ' (' + pct + '%)';
                    }
                }
            }
        }
    };
}

function getBarChartOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } },
            x: { ticks: { maxRotation: 45, minRotation: 0, font: { size: 10 } } }
        }
    };
}

// ============ INIT ============
function initDashboardCharts() {
    // Wait for Chart.js to load
    if (typeof Chart === 'undefined') {
        setTimeout(initDashboardCharts, 100);
        return;
    }

    createVisitorChart('daily');
    createDeviceChart();
    createAgendaStatusChart();
    createAgendaTrenChart();
    createTopOpdChart();
    createAgendaKategoriChart();
    createTrendKategoriChart();
    createKomposisiKontenChart();
    createBrowserChart();
    createKotaChart();
}

// Expose globally for tab switching
window.switchVisitorChart = switchVisitorChart;

// Re-render charts when bootstrap tab becomes visible (hidden canvas has 0 size otherwise)
function refreshVisibleCharts() {
    [visitorChart, deviceChart, agendaStatusChart, agendaTrenChart, topOpdChart, agendaKategoriChart, trendKategoriChart, komposisiKontenChart, browserChart, kotaChart].forEach(function(c) {
        if (c && typeof c.resize === 'function') { try { c.resize(); c.update(); } catch(e) {} }
    });
}

document.addEventListener('shown.bs.tab', function(e) {
    // slight delay agar tab sudah display:block
    setTimeout(refreshVisibleCharts, 80);
});

// Auto-init on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initDashboardCharts();
    // fallback: refresh after window load
    window.addEventListener('load', function() { setTimeout(refreshVisibleCharts, 200); });
});