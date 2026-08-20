/**
 * AquaPulse ESM Module - Theme Manager
 * Handles Dark/Light Cyber Mode toggle and Key 'T' shortcut listener.
 */

export class ThemeManager {
    constructor() {
        this.init();
    }

    init() {
        window.addEventListener('keydown', (e) => {
            if (e.key && e.key.toLowerCase() === 't') {
                this.toggleTheme();
            }
        });
    }

    toggleTheme() {
        document.documentElement.classList.toggle('dark');
        this.playCyberTone(600, 'sine', 0.1);
    }

    playCyberTone(freq = 440, type = 'sine', duration = 0.1) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = type;
            osc.frequency.setValueAtTime(freq, ctx.currentTime);
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.linearRampToValueAtTime(0.01, ctx.currentTime + duration);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + duration);
        } catch (e) {}
    }
}
