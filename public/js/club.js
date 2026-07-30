/**
 * Club Cyber Visualizer & Interactive Audio Studio
 */
let animationFrameId = null;
let audioCtx = null;
let analyser = null;
let dataArray = null;

export function initClubVisualizer() {
    const canvas = document.getElementById('clubCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    const resize = () => {
        canvas.width = canvas.parentElement ? canvas.parentElement.clientWidth : window.innerWidth;
        canvas.height = canvas.parentElement ? canvas.parentElement.clientHeight : window.innerHeight;
    };
    resize();
    window.addEventListener('resize', resize);

    // Glowing Particles Pool
    const particles = [];
    const particleCount = 120;
    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.random() * 3 + 1,
            color: `hsl(${Math.random() * 360}, 100%, 65%)`,
            vx: (Math.random() - 0.5) * 2,
            vy: (Math.random() - 0.5) * 2,
            pulse: Math.random() * Math.PI,
        });
    }

    let angle = 0;

    const render = () => {
        ctx.fillStyle = 'rgba(5, 5, 15, 0.25)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        angle += 0.02;
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;

        // Draw Cyber Neon Equalizer Waves
        ctx.save();
        ctx.lineWidth = 3;

        for (let j = 0; j < 3; j++) {
            ctx.beginPath();
            const colors = ['#f43f5e', '#8b5cf6', '#3b82f6'];
            ctx.strokeStyle = colors[j];
            ctx.shadowBlur = 15;
            ctx.shadowColor = colors[j];

            for (let x = 0; x < canvas.width; x += 10) {
                const y = centerY + Math.sin(x * 0.008 + angle + j) * 45 + Math.cos(angle * 1.5) * 20;
                if (x === 0) ctx.moveTo(x, y);
                else ctx.lineTo(x, y);
            }
            ctx.stroke();
        }
        ctx.restore();

        // Render Floating Pulsing Club Particles
        particles.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;
            p.pulse += 0.05;

            if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
            if (p.y < 0 || p.y > canvas.height) p.vy *= -1;

            const currentRadius = p.radius + Math.sin(p.pulse) * 1.5;

            ctx.save();
            ctx.beginPath();
            ctx.arc(p.x, p.y, Math.max(0.5, currentRadius), 0, Math.PI * 2);
            ctx.fillStyle = p.color;
            ctx.shadowBlur = 12;
            ctx.shadowColor = p.color;
            ctx.fill();
            ctx.restore();
        });

        // Draw Central Equalizer Bar Grid
        const barWidth = 8;
        const gap = 4;
        const numBars = Math.min(64, Math.floor(canvas.width / (barWidth + gap)));
        const startX = (canvas.width - (numBars * (barWidth + gap))) / 2;

        for (let i = 0; i < numBars; i++) {
            const h = Math.abs(Math.sin(angle * 2 + i * 0.2)) * 120 + 15;
            const barX = startX + i * (barWidth + gap);
            const barY = centerY - h / 2;

            const grad = ctx.createLinearGradient(barX, barY, barX, barY + h);
            grad.addColorStop(0, '#ec4899');
            grad.addColorStop(0.5, '#8b5cf6');
            grad.addColorStop(1, '#06b6d4');

            ctx.fillStyle = grad;
            ctx.fillRect(barX, barY, barWidth, h);
        }

        animationFrameId = requestAnimationFrame(render);
    };

    if (animationFrameId) cancelAnimationFrame(animationFrameId);
    render();
}

export function toggleClubAudio() {
    const audio = document.getElementById('audioTrack');
    const playBtn = document.getElementById('btnPlayClub');
    const pauseBtn = document.getElementById('btnPauseClub');
    const eqBadge = document.getElementById('equalizerStatusBadge');

    if (!audio) return;

    if (audio.paused) {
        audio.play().then(() => {
            if (playBtn) playBtn.classList.add('hidden');
            if (pauseBtn) pauseBtn.classList.remove('hidden');
            if (eqBadge) {
                eqBadge.innerText = '🎵 Playing Live Techno & Cyber Beats';
                eqBadge.className = 'px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-full text-xs font-mono font-bold flex items-center gap-1.5 animate-pulse';
            }
        }).catch(err => {
            console.error('Audio playback failed:', err);
            alert('Please interact with the page to play audio.');
        });
    } else {
        audio.pause();
        if (playBtn) playBtn.classList.remove('hidden');
        if (pauseBtn) pauseBtn.classList.add('hidden');
        if (eqBadge) {
            eqBadge.innerText = '⏸️ Audio Paused';
            eqBadge.className = 'px-3 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/40 rounded-full text-xs font-mono font-bold';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initClubVisualizer();
});

window.initClubVisualizer = initClubVisualizer;
window.toggleClubAudio = toggleClubAudio;
