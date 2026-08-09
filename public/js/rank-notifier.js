/**
 * Site-Wide Sandika Rank & CP Celebration Notification Engine
 * Provides Canvas Confetti bursts, Web Audio arpeggio chimes, and floating toast banners across the entire website.
 */
(function() {
    // 1. Create Floating Toast Container & Canvas element
    const container = document.createElement('div');
    container.id = 'rank-celebration-container';
    container.className = 'fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none';
    document.body.appendChild(container);

    const canvas = document.createElement('canvas');
    canvas.id = 'rank-confetti-canvas';
    canvas.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;pointer-events:none;z-index:9998;';
    document.body.appendChild(canvas);

    let ctx = canvas.getContext('2d');
    let particles = [];
    let animationFrame = null;

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    // 2. High-Performance Particle Confetti Burst
    function launchConfetti() {
        const colors = ['#f59e0b', '#ec4899', '#8b5cf6', '#10b981', '#3b82f6', '#ef4444', '#fbbf24'];
        const count = 90;
        
        for (let i = 0; i < count; i++) {
            particles.push({
                x: window.innerWidth / 2 + (Math.random() * 200 - 100),
                y: window.innerHeight / 3,
                vx: (Math.random() - 0.5) * 14,
                vy: Math.random() * -12 - 4,
                size: Math.random() * 9 + 5,
                color: colors[Math.floor(Math.random() * colors.length)],
                rotation: Math.random() * 360,
                rSpeed: (Math.random() - 0.5) * 10,
                opacity: 1,
                gravity: 0.35
            });
        }

        if (!animationFrame) {
            animateConfetti();
        }
    }

    function animateConfetti() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        for (let i = particles.length - 1; i >= 0; i--) {
            let p = particles[i];
            p.x += p.vx;
            p.y += p.vy;
            p.vy += p.gravity;
            p.rotation += p.rSpeed;
            p.opacity -= 0.008;

            if (p.opacity <= 0 || p.y > window.innerHeight) {
                particles.splice(i, 1);
                continue;
            }

            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate((p.rotation * Math.PI) / 180);
            ctx.globalAlpha = p.opacity;
            ctx.fillStyle = p.color;
            ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
            ctx.restore();
        }

        if (particles.length > 0) {
            animationFrame = requestAnimationFrame(animateConfetti);
        } else {
            animationFrame = null;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }

    // 3. Web Audio Chime Synthesizer
    function playCelebrationChime() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            
            const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
            notes.forEach((freq, index) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.value = freq;
                
                gain.gain.setValueAtTime(0.12, ctx.currentTime + index * 0.08);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + index * 0.08 + 0.3);
                
                osc.connect(gain);
                gain.connect(ctx.destination);
                
                osc.start(ctx.currentTime + index * 0.08);
                osc.stop(ctx.currentTime + index * 0.08 + 0.3);
            });
        } catch (e) {
            // Audio context permission fallback
        }
    }

    // 4. Global Toast Notification Function
    window.triggerRankCelebration = function(rank, cpGained, customMessage) {
        launchConfetti();
        playCelebrationChime();

        const toast = document.createElement('div');
        toast.className = 'pointer-events-auto w-80 sm:w-96 p-4 rounded-3xl bg-gradient-to-r from-gray-900/95 via-indigo-950/95 to-black/95 border-2 border-amber-400/80 shadow-[0_0_30px_rgba(245,158,11,0.5)] backdrop-blur-md text-white font-sans transition-all duration-500 transform translate-x-12 opacity-0 flex items-start gap-3.5';

        const rankTitle = rank && rank.rank_title ? rank.rank_title : 'Agent Rank';
        const rankLevel = rank && rank.level ? rank.level : '3';
        const totalXp = rank && rank.xp ? rank.xp : 'CP';
        const cpText = cpGained ? `+${cpGained} CP!` : '+CP Awarded!';

        toast.innerHTML = `
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-rose-600 flex items-center justify-center text-xl shadow-lg shrink-0 border border-amber-300">
                👑
            </div>
            <div class="flex-1 space-y-1 overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono font-bold text-amber-400 uppercase tracking-wider">🎉 RANK & CP UPDATED</span>
                    <span class="px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-mono text-[10px] font-extrabold border border-amber-500/40">${cpText}</span>
                </div>
                <h4 class="font-extrabold text-sm text-white truncate">${customMessage || 'Sandika Combat Power Gained!'}</h4>
                <p class="text-[11px] font-mono text-gray-300">
                    Title: <strong class="text-amber-300">${rankTitle}</strong> • Lvl <strong class="text-emerald-400">${rankLevel}</strong> (${totalXp} Total CP)
                </p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white font-bold text-xs p-1">✕</button>
        `;

        container.appendChild(toast);

        // Animate toast entry
        setTimeout(() => {
            toast.classList.remove('translate-x-12', 'opacity-0');
        }, 50);

        // Auto remove toast after 6 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-12');
            setTimeout(() => toast.remove(), 500);
        }, 6000);
    };

    // 5. Global Fetch Interceptor to Automatically Trigger Notifications Website-Wide
    const originalFetch = window.fetch;
    window.fetch = async function(...args) {
        const response = await originalFetch.apply(this, args);
        try {
            const clone = response.clone();
            const data = await clone.json();
            if (data && data.rank) {
                const cpMatch = data.message ? data.message.match(/\+(\d+)\s*CP/) : null;
                const cpValue = cpMatch ? cpMatch[1] : null;
                window.triggerRankCelebration(data.rank, cpValue, data.message);
            }
        } catch (e) {
            // Ignore non-json responses
        }
        return response;
    };
})();
