/**
 * React-Toastify Visual Notifications & Notification Audio Sound Engine
 */

class ReactToastifyEngine {
    constructor() {
        this.container = null;
        this.audioCtx = null;
        this.init();
    }

    init() {
        if (document.getElementById('reactToastifyContainer')) return;
        const container = document.createElement('div');
        container.id = 'reactToastifyContainer';
        container.className = 'fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none max-w-sm w-full px-4';
        document.body.appendChild(container);
        this.container = container;

        // Auto flash session alert messages if present in DOM
        document.addEventListener('DOMContentLoaded', () => {
            const flashSuccess = document.querySelector('[data-flash-success]')?.getAttribute('data-flash-success');
            const flashError = document.querySelector('[data-flash-error]')?.getAttribute('data-flash-error');

            if (flashSuccess) this.show(flashSuccess, 'success');
            if (flashError) this.show(flashError, 'error');
        });
    }

    getAudioContext() {
        if (!this.audioCtx) {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (AudioContext) {
                this.audioCtx = new AudioContext();
            }
        }
        if (this.audioCtx && this.audioCtx.state === 'suspended') {
            this.audioCtx.resume();
        }
        return this.audioCtx;
    }

    playNotificationAudio(type = 'success') {
        try {
            const ctx = this.getAudioContext();
            if (!ctx) return;

            const now = ctx.currentTime;
            const osc = ctx.createOscillator();
            const osc2 = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc2.type = 'triangle';

            if (type === 'success') {
                // High-pitched crystal success chime (C5 -> E5 -> G5 -> C6)
                osc.frequency.setValueAtTime(523.25, now); // C5
                osc.frequency.setValueAtTime(659.25, now + 0.08); // E5
                osc.frequency.setValueAtTime(783.99, now + 0.16); // G5
                osc.frequency.setValueAtTime(1046.50, now + 0.24); // C6

                osc2.frequency.setValueAtTime(1046.50, now + 0.24);

                gain.gain.setValueAtTime(0.01, now);
                gain.gain.linearRampToValueAtTime(0.25, now + 0.05);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.5);

                osc.connect(gain);
                osc2.connect(gain);
                gain.connect(ctx.destination);

                osc.start(now);
                osc2.start(now + 0.24);
                osc.stop(now + 0.5);
                osc2.stop(now + 0.5);
            } else if (type === 'error') {
                // Low alert chime
                osc.frequency.setValueAtTime(329.63, now); // E4
                osc.frequency.setValueAtTime(261.63, now + 0.12); // C4

                gain.gain.setValueAtTime(0.2, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.4);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start(now);
                osc.stop(now + 0.4);
            } else {
                // Info chime (E5 -> B5)
                osc.frequency.setValueAtTime(659.25, now);
                osc.frequency.setValueAtTime(987.77, now + 0.1);

                gain.gain.setValueAtTime(0.18, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start(now);
                osc.stop(now + 0.35);
            }
        } catch (e) {
            console.warn('Audio play error:', e);
        }
    }

    show(message, type = 'success', duration = 4000) {
        if (!this.container) this.init();

        // Play audio chime
        this.playNotificationAudio(type);

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto flex flex-col rounded-2xl p-4 shadow-[0_15px_40px_rgba(0,0,0,0.6)] backdrop-blur-2xl border transition-all duration-500 transform translate-x-10 opacity-0 relative overflow-hidden text-xs font-sans ${
            type === 'success' 
                ? 'bg-emerald-950/90 border-emerald-500/40 text-emerald-100' 
                : type === 'error' 
                ? 'bg-rose-950/90 border-rose-500/40 text-rose-100' 
                : 'bg-indigo-950/90 border-indigo-500/40 text-indigo-100'
        }`;

        const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️';

        toast.innerHTML = `
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="text-base">${icon}</span>
                    <span class="font-semibold leading-snug">${escapeToastHtml(message)}</span>
                </div>
                <button class="toast-close-btn text-gray-400 hover:text-white text-xs p-1 font-bold">✕</button>
            </div>
            <div class="toast-progress-bar absolute bottom-0 left-0 h-1 ${
                type === 'success' ? 'bg-emerald-400' : type === 'error' ? 'bg-rose-400' : 'bg-indigo-400'
            } transition-all ease-linear" style="width: 100%; transition-duration: ${duration}ms;"></div>
        `;

        this.container.appendChild(toast);

        // Trigger entry animation
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-10', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
            const bar = toast.querySelector('.toast-progress-bar');
            if (bar) bar.style.width = '0%';
        });

        // Close button handler
        const closeBtn = toast.querySelector('.toast-close-btn');
        const removeToast = () => {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-10', 'opacity-0');
            setTimeout(() => toast.remove(), 400);
        };

        if (closeBtn) closeBtn.addEventListener('click', removeToast);

        // Auto remove after duration
        setTimeout(removeToast, duration);
    }
}

function escapeToastHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

const toastEngine = new ReactToastifyEngine();

export function showToast(message, type = 'success', duration = 4000) {
    toastEngine.show(message, type, duration);
}

export function playNotificationAudio(type = 'success') {
    toastEngine.playNotificationAudio(type);
}

// Bind to window object for website-wide availability
window.showToast = showToast;
window.playNotificationAudio = playNotificationAudio;
window.ReactToastify = toastEngine;
