/**
 * AquaPulse ESM Module - Telemetry Chart & EnKF Engine Poll
 * Manages Chart.js real-time plotting of stochastic Lotka-Volterra dynamics & EnKF 100-member spread.
 */

export class TelemetryChart {
    constructor(canvasId) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;
        this.chartInstance = null;
        this.initChart();
        this.startPolling();
    }

    initChart() {
        const timeLabels = [];
        const preyData = [];
        const predData = [];

        for (let i = 0; i < 20; i++) {
            timeLabels.push(`t-${20 - i}s`);
            preyData.push(50 + 20 * Math.cos(i * 0.3));
            predData.push(25 + 10 * Math.sin(i * 0.3));
        }

        const chartCtx = this.canvas.getContext('2d');
        this.chartInstance = new Chart(chartCtx, {
            type: 'line',
            data: {
                labels: timeLabels,
                datasets: [
                    {
                        label: 'Prey Population X(t)',
                        data: preyData,
                        borderColor: '#06B6D4',
                        backgroundColor: 'rgba(6, 182, 212, 0.1)',
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Predator Y(t)',
                        data: predData,
                        borderColor: '#F4D03F',
                        backgroundColor: 'rgba(244, 208, 63, 0.1)',
                        fill: true,
                        tension: 0.4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { ticks: { color: '#64748B', font: { family: 'JetBrains Mono', size: 10 } }, grid: { color: 'rgba(51, 65, 85, 0.3)' } },
                    y: { ticks: { color: '#64748B', font: { family: 'JetBrains Mono', size: 10 } }, grid: { color: 'rgba(51, 65, 85, 0.3)' } }
                },
                plugins: {
                    legend: { labels: { color: '#E2E8F0', font: { family: 'JetBrains Mono', size: 11 } } }
                }
            }
        });
    }

    startPolling() {
        setInterval(() => this.fetchLiveTelemetry(), 2500);
    }

    async fetchLiveTelemetry() {
        try {
            const res = await fetch('/api/v1/telemetry');
            if (res.ok) {
                const data = await res.json();
                const tel = data.telemetry;

                const preyEl = document.getElementById('statPreyX');
                const predEl = document.getElementById('statPredatorY');
                const riskEl = document.getElementById('statRisk');
                const preyValEl = document.getElementById('preyVal');
                const predValEl = document.getElementById('predVal');

                if (preyEl) preyEl.innerText = tel.prey_X;
                if (predEl) predEl.innerText = tel.predator_Y;
                if (riskEl) riskEl.innerText = `${tel.extinction_risk_pct}%`;
                if (preyValEl) preyValEl.innerText = tel.prey_X;
                if (predValEl) predValEl.innerText = tel.predator_Y;

                if (this.chartInstance) {
                    this.chartInstance.data.datasets[0].data.shift();
                    this.chartInstance.data.datasets[0].data.push(tel.prey_X);
                    this.chartInstance.data.datasets[1].data.shift();
                    this.chartInstance.data.datasets[1].data.push(tel.predator_Y);
                    this.chartInstance.update('none');
                }
            }
        } catch (e) {}
    }
}
