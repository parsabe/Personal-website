/**
 * AquaPulse ESM Module - Johnny Silverhand Cyberpunk Glitch Engine
 * Handles Cyberpunk 2077 Glitch Effects, Web Audio Synthesizer, Shortcut Key J, and Johnny Hologram Overlay.
 */

export class JohnnyGlitch {
    constructor() {
        this.modal = document.getElementById('johnnyModal');
        this.init();
    }

    init() {
        window.addEventListener('keydown', (e) => {
            if (e.key && e.key.toLowerCase() === 'j') {
                this.triggerGlitch();
            }
        });
    }

    playCyberGlitchSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(120, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(40, ctx.currentTime + 0.5);
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.linearRampToValueAtTime(0.01, ctx.currentTime + 0.5);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.5);
        } catch (e) {}
    }

    triggerGlitch() {
        this.playCyberGlitchSound();
        document.body.classList.add('johnny-glitch-active');

        setTimeout(() => {
            document.body.classList.remove('johnny-glitch-active');
        }, 600);

        if (this.modal) {
            this.modal.classList.remove('hidden');
            this.modal.classList.add('flex');
        }
    }

    closeModal() {
        if (this.modal) {
            this.modal.classList.add('hidden');
            this.modal.classList.remove('flex');
        }
    }

    executeAction(actionType) {
        this.playCyberGlitchSound();
        alert(`Johnny Silverhand Cyberpunk Action executed: [${actionType.toUpperCase()}]`);
        this.closeModal();
    }
}
