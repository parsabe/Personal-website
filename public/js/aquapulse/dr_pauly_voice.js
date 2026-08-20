/**
 * AquaPulse ESM Module - Dr. Pauly & Johnny Silverhand Voice Comms Engine
 * Handles Text-to-Speech (TTS), Speech-to-Text (Microphone STT), and Ollama AI LLM Comms.
 */

export class DrPaulyVoiceEngine {
    constructor() {
        this.synth = window.speechSynthesis || null;
        this.recognition = null;
        this.currentPersona = 'dr_pauly'; // 'dr_pauly' or 'johnny_silverhand'
        this.isRecording = false;

        this.initSpeechRecognition();
    }

    initSpeechRecognition() {
        const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRec) {
            this.recognition = new SpeechRec();
            this.recognition.continuous = false;
            this.recognition.interimResults = false;
            this.recognition.lang = 'en-US';

            this.recognition.onstart = () => {
                this.isRecording = true;
                const micBtn = document.getElementById('btnMic');
                if (micBtn) {
                    micBtn.classList.add('bg-red-600', 'animate-pulse');
                    micBtn.innerText = 'Listening...';
                }
            };

            this.recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                const inputEl = document.getElementById('commsInput');
                if (inputEl) inputEl.value = transcript;
                this.sendPrompt(transcript);
            };

            this.recognition.onend = () => {
                this.isRecording = false;
                const micBtn = document.getElementById('btnMic');
                if (micBtn) {
                    micBtn.classList.remove('bg-red-600', 'animate-pulse');
                    micBtn.innerText = '🎤 Voice';
                }
            };
        }
    }

    toggleMicrophone() {
        if (!this.recognition) {
            alert('Speech Recognition is not supported on this browser. Please type your query.');
            return;
        }

        if (this.isRecording) {
            this.recognition.stop();
        } else {
            this.recognition.start();
        }
    }

    setPersona(persona) {
        this.currentPersona = persona;
        const badge = document.getElementById('personaBadge');
        if (badge) {
            badge.innerText = persona === 'johnny_silverhand' ? 'JOHNNY SILVERHAND (CYBERPUNK)' : 'DR. PAULY (CHIEF ECOLOGIST)';
            badge.className = persona === 'johnny_silverhand' ? 'text-xs font-mono text-cyber-gold font-bold' : 'text-xs font-mono text-cyber-cyan font-bold';
        }
    }

    speakText(text, isJohnny = false) {
        if (!this.synth) return;
        this.synth.cancel();

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = isJohnny ? 1.1 : 0.95;
        utterance.pitch = isJohnny ? 0.8 : 1.0;

        const voices = this.synth.getVoices();
        const englishVoice = voices.find(v => v.lang.includes('en') || v.name.includes('David') || v.name.includes('Google'));
        if (englishVoice) utterance.voice = englishVoice;

        this.synth.speak(utterance);
    }

    async sendPrompt(overridePrompt = null) {
        const inputEl = document.getElementById('commsInput');
        const prompt = overridePrompt || (inputEl ? inputEl.value : '');

        if (!prompt || !prompt.trim()) return;

        const outputEl = document.getElementById('commsOutput');
        if (outputEl) {
            outputEl.innerHTML = `<span class="text-cyber-cyan animate-pulse"><i class="fa-solid fa-spinner fa-spin"></i> Contacting Ollama AI Engine (llama3)...</span>`;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const res = await fetch('/api/v1/ollama/dialogue', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    prompt: prompt,
                    persona: this.currentPersona,
                    lang: 'EN',
                }),
            });

            if (res.ok) {
                const data = await res.json();
                const text = data.response;
                const isJohnny = this.currentPersona === 'johnny_silverhand';

                if (outputEl) {
                    outputEl.innerHTML = `
                        <div class="${isJohnny ? 'text-cyber-gold' : 'text-cyber-cyan'} font-bold mb-1">
                            ${isJohnny ? '<i class="fa-solid fa-skull"></i> Johnny Silverhand:' : '<i class="fa-solid fa-user-doctor"></i> Dr. Pauly:'}
                        </div>
                        <div class="text-slate-200">${text}</div>
                        <div class="text-[10px] text-slate-500 mt-2 font-mono">Source: ${data.source} (${data.model})</div>
                    `;
                }

                // Play Audio Text-to-Speech Output
                this.speakText(text, isJohnny);
                if (inputEl) inputEl.value = '';
            }
        } catch (e) {
            if (outputEl) {
                outputEl.innerHTML = `<span class="text-cyber-red">Comms error. AI engine offline.</span>`;
            }
        }
    }
}
