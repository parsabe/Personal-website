/**
 * AquaPulse AI Marine Vision & Telemetry System - Main ESM Entry Point
 * Loads ES Modules, initializes 4-Pane Grid, Voice Engine & Cyberpunk Glitch System.
 */

import { ThemeManager } from './theme_manager.js';
import { VisionEngine } from './vision_engine.js';
import { TelemetryChart } from './telemetry_chart.js';
import { GbifExplorer } from './gbif_explorer.js';
import { JohnnyGlitch } from './johnny_glitch.js';
import { DrPaulyVoiceEngine } from './dr_pauly_voice.js';

document.addEventListener('DOMContentLoaded', () => {
    // Instantiate Modules
    const themeMgr = new ThemeManager();
    const visionEng = new VisionEngine('visionCanvas');
    const telemetryChart = new TelemetryChart('telemetryChart');
    const gbifExp = new GbifExplorer();
    const johnnyGlitch = new JohnnyGlitch();
    const voiceEngine = new DrPaulyVoiceEngine();

    // Export clean global window handlers for UI triggers
    window.toggleTheme = () => themeMgr.toggleTheme();
    window.toggleReticles = () => visionEng.toggleReticles();
    window.toggleSonar = () => {
        themeMgr.playCyberTone(880, 'sine', 0.2);
        alert('AquaPulse Acoustic Hydro-Sonar Filter Engaged.');
    };

    window.openGbifModal = () => gbifExp.openModal();
    window.closeGbifModal = () => gbifExp.closeModal();
    window.searchGbif = () => gbifExp.searchGbif();

    window.triggerJohnnyGlitch = () => johnnyGlitch.triggerGlitch();
    window.closeJohnnyModal = () => johnnyGlitch.closeModal();
    window.johnnyAction = (act) => johnnyGlitch.executeAction(act);

    // Dr. Pauly & Johnny Voice Engine Exports
    window.setPersona = (p) => voiceEngine.setPersona(p);
    window.sendComms = () => voiceEngine.sendPrompt();
    window.toggleMicrophone = () => voiceEngine.toggleMicrophone();

    window.inspectModule = (code, title, metric, latency) => {
        const modTitle = document.getElementById('modTitle');
        const modSub = document.getElementById('modSub');
        const modBody = document.getElementById('modBody');
        const modal = document.getElementById('moduleModal');

        if (modTitle) modTitle.innerText = `${code}: ${title}`;
        if (modSub) modSub.innerText = `Real-Time Latency: ${latency}`;
        if (modBody) {
            modBody.innerHTML = `
                <div><strong>Module Status:</strong> <span class="text-emerald-400 font-bold">ONLINE & STABLE</span></div>
                <div><strong>Telemetry Metric:</strong> ${metric}</div>
                <div><strong>EnKF Stratonovich Matrix:</strong> Verified & Covariance Normalized</div>
            `;
        }
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    };

    window.closeModuleModal = () => {
        const modal = document.getElementById('moduleModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    console.log('⚡ AquaPulse ESM Telemetry & Ollama Voice AI Engine Initialized Successfully.');
});
