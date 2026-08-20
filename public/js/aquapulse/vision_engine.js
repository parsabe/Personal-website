/**
 * AquaPulse ESM Module - Vision Engine
 * Renders Marine Optical Telemetry Stream, YOLOv8 Bounding Reticles, and BotSORT Trajectory Traces.
 */

export class VisionEngine {
    constructor(canvasId) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;
        this.ctx = this.canvas.getContext('2d');
        this.reticlesOn = true;
        this.frameCounter = 0;

        this.marineSpecimens = [
            { id: 101, name: 'Thunnus albacares', conf: '98.6%', x: 140, y: 160, vx: 1.5, vy: 0.8, color: '#06B6D4' },
            { id: 102, name: 'Delphinus delphis', conf: '96.2%', x: 290, y: 220, vx: -1.2, vy: -0.5, color: '#10B981' },
            { id: 103, name: 'Carcharodon carcharias', conf: '99.1%', x: 440, y: 120, vx: -0.9, vy: 1.1, color: '#EF4444' },
            { id: 104, name: 'Chelonia mydas', conf: '94.8%', x: 210, y: 300, vx: 0.8, vy: -0.6, color: '#F4D03F' },
        ];

        this.init();
    }

    init() {
        this.resize();
        window.addEventListener('resize', () => this.resize());
        this.loop();
    }

    resize() {
        if (!this.canvas) return;
        this.canvas.width = this.canvas.parentElement.clientWidth;
        this.canvas.height = this.canvas.parentElement.clientHeight;
    }

    toggleReticles() {
        this.reticlesOn = !this.reticlesOn;
        const btn = document.getElementById('btnReticle');
        if (btn) {
            btn.innerText = `Reticles: ${this.reticlesOn ? 'ON' : 'OFF'}`;
            btn.className = this.reticlesOn ? 'px-2.5 py-1 rounded bg-cyber-cyan text-slate-950 font-bold' : 'px-2.5 py-1 rounded bg-slate-800 text-slate-400';
        }
    }

    loop() {
        if (!this.canvas || !this.ctx) return;
        this.frameCounter++;
        const fc = document.getElementById('frameCount');
        if (fc) fc.innerText = this.frameCounter;

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Water Background Gradient
        const bgGrad = this.ctx.createLinearGradient(0, 0, 0, this.canvas.height);
        bgGrad.addColorStop(0, '#0F172A');
        bgGrad.addColorStop(1, '#0284C7');
        this.ctx.fillStyle = bgGrad;
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

        // Particulate Physics
        this.ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
        for (let i = 0; i < 35; i++) {
            const px = (Math.sin(this.frameCounter * 0.02 + i) * 0.5 + 0.5) * this.canvas.width;
            const py = ((this.frameCounter * 0.5 + i * 20) % this.canvas.height);
            this.ctx.beginPath();
            this.ctx.arc(px, py, (i % 3) + 1, 0, Math.PI * 2);
            this.ctx.fill();
        }

        // Marine Specimen Vectors & YOLO Reticles
        this.marineSpecimens.forEach(spec => {
            spec.x += spec.vx;
            spec.y += spec.vy;

            if (spec.x < 50 || spec.x > this.canvas.width - 150) spec.vx *= -1;
            if (spec.y < 50 || spec.y > this.canvas.height - 100) spec.vy *= -1;

            this.ctx.fillStyle = spec.color;
            this.ctx.beginPath();
            this.ctx.arc(spec.x, spec.y, 8, 0, Math.PI * 2);
            this.ctx.fill();

            if (this.reticlesOn) {
                this.ctx.strokeStyle = spec.color;
                this.ctx.lineWidth = 1.5;
                const w = 110;
                const h = 70;
                this.ctx.strokeRect(spec.x - 20, spec.y - 20, w, h);

                this.ctx.fillStyle = 'rgba(15, 23, 42, 0.85)';
                this.ctx.fillRect(spec.x - 20, spec.y - 38, 120, 16);
                this.ctx.fillStyle = '#FFFFFF';
                this.ctx.font = '10px JetBrains Mono';
                this.ctx.fillText(`${spec.name} (${spec.conf})`, spec.x - 16, spec.y - 26);
            }
        });

        requestAnimationFrame(() => this.loop());
    }
}
