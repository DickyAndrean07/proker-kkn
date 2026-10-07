import Chart from 'chart.js/auto';

const chartInstances = new WeakMap();

function readChartData(canvas) {
    try {
        return {
            labels: JSON.parse(canvas.dataset.chartLabels || '[]'),
            values: JSON.parse(canvas.dataset.chartValues || '[]').map((value) => Number(value) || 0),
        };
    } catch {
        return { labels: [], values: [] };
    }
}

function dashboardColors() {
    const styles = getComputedStyle(document.body);

    return {
        moss: styles.getPropertyValue('--admin-primary').trim() || '#2E5E3E',
        deepMoss: styles.getPropertyValue('--admin-primary-strong').trim() || '#173E2A',
        amber: styles.getPropertyValue('--admin-accent').trim() || '#D6A928',
        muted: styles.getPropertyValue('--admin-muted').trim() || '#817C70',
        line: styles.getPropertyValue('--admin-border').trim() || '#E4DDD0',
        surface: styles.getPropertyValue('--admin-surface').trim() || '#FFFFFF',
    };
}

function sharedOptions(colors) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        resizeDelay: 80,
        plugins: {
            legend: { display: false },
            tooltip: {
                displayColors: false,
                backgroundColor: colors.deepMoss,
                padding: 10,
                titleFont: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                bodyFont: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                callbacks: {
                    label: (context) => `${context.label}: ${context.parsed.y ?? context.parsed} entri`,
                },
            },
        },
    };
}

function createBarChart(canvas, labels, values, colors) {
    return new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: colors.moss,
                hoverBackgroundColor: colors.deepMoss,
                borderRadius: 4,
                borderSkipped: false,
                barPercentage: 0.58,
                categoryPercentage: 0.68,
            }],
        },
        options: {
            ...sharedOptions(colors),
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: colors.muted,
                        font: { family: 'Plus Jakarta Sans', size: 10, weight: '700' },
                        maxRotation: 0,
                        autoSkip: false,
                    },
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...values, 1) + 1,
                    grid: { color: colors.line, drawTicks: false },
                    border: { display: false },
                    ticks: {
                        color: colors.muted,
                        padding: 8,
                        precision: 0,
                        font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' },
                    },
                },
            },
        },
    });
}

function createDoughnutChart(canvas, labels, values, colors) {
    return new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: [colors.moss, colors.amber, '#7A8F6B', '#B8B6AC'],
                borderColor: colors.surface,
                borderWidth: 3,
                hoverOffset: 4,
            }],
        },
        options: {
            ...sharedOptions(colors),
            cutout: '72%',
            plugins: {
                ...sharedOptions(colors).plugins,
                tooltip: {
                    ...sharedOptions(colors).plugins.tooltip,
                    callbacks: {
                        label: (context) => `${context.label}: ${context.parsed} entri`,
                    },
                },
            },
        },
    });
}

export function initDashboardCharts() {
    document.querySelectorAll('[data-dashboard-chart]').forEach((canvas) => {
        const { labels, values } = readChartData(canvas);

        if (!canvas || labels.length === 0 || values.every((value) => value === 0)) {
            return;
        }

        chartInstances.get(canvas)?.destroy();

        const colors = dashboardColors();
        const chartType = canvas.dataset.dashboardChart;
        const chart = chartType === 'doughnut'
            ? createDoughnutChart(canvas, labels, values, colors)
            : createBarChart(canvas, labels, values, colors);

        chartInstances.set(canvas, chart);
    });
}
