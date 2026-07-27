/**
 * Sandika Arkham Portal ESM Module
 */
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Voice Log Analyzer
    const analyzeVoiceBtn = document.getElementById('btn-analyze-voice');
    const voiceStatus = document.getElementById('voice-status');

    if (analyzeVoiceBtn) {
        analyzeVoiceBtn.addEventListener('click', async () => {
            voiceStatus.innerHTML = '<span class="text-amber-400 font-mono animate-pulse">⏳ Analyzing voice audio frequency wave...</span>';
            analyzeVoiceBtn.disabled = true;

            try {
                const response = await fetch('/sandika/voice-log', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    voiceStatus.innerHTML = `<span class="text-emerald-400 font-mono">✅ ${data.message}</span>`;
                    updateRankUI(data.rank);
                } else {
                    voiceStatus.innerHTML = `<span class="text-rose-400 font-mono">❌ Analysis failed. Please sign in to earn XP.</span>`;
                }
            } catch (err) {
                voiceStatus.innerHTML = `<span class="text-rose-400 font-mono">❌ System connection error.</span>`;
            } finally {
                analyzeVoiceBtn.disabled = false;
            }
        });
    }

    // File Processing Dropzone
    const fileInput = document.getElementById('sandika-file-input');
    const fileStatus = document.getElementById('file-status');

    if (fileInput) {
        fileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);

            fileStatus.innerHTML = `<span class="text-amber-400 font-mono animate-pulse">⏳ Ingesting ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)...</span>`;

            try {
                const response = await fetch('/sandika/file-upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    fileStatus.innerHTML = `<span class="text-emerald-400 font-mono">✅ ${data.message}</span>`;
                    updateRankUI(data.rank);
                } else {
                    fileStatus.innerHTML = `<span class="text-rose-400 font-mono">❌ Ingestion failed.</span>`;
                }
            } catch (err) {
                fileStatus.innerHTML = `<span class="text-rose-400 font-mono">❌ Vault upload error.</span>`;
            }
        });
    }

    function updateRankUI(rank) {
        if (!rank) return;
        const xpElem = document.getElementById('user-xp-val');
        const levelElem = document.getElementById('user-level-val');
        const titleElem = document.getElementById('user-title-val');
        const progressBar = document.getElementById('xp-progress-bar');

        if (xpElem) xpElem.textContent = rank.xp;
        if (levelElem) levelElem.textContent = rank.level;
        if (titleElem) titleElem.textContent = rank.rank_title;
        if (progressBar) {
            const progress = (rank.xp % 100);
            progressBar.style.width = `${progress}%`;
        }
    }
});
