/**
 * Sandika Full Concept ESM Module
 */
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Tab Navigation Switcher
    const tabButtons = document.querySelectorAll('.sandika-tab-btn');
    const tabContents = document.querySelectorAll('.sandika-tab-content');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-target');
            
            tabButtons.forEach(b => b.classList.remove('bg-indigo-600/40', 'border-indigo-400', 'text-white'));
            tabButtons.forEach(b => b.classList.add('bg-black/30', 'border-white/10', 'text-gray-400'));
            
            btn.classList.remove('bg-black/30', 'border-white/10', 'text-gray-400');
            btn.classList.add('bg-indigo-600/40', 'border-indigo-400', 'text-white');

            tabContents.forEach(c => {
                if (c.id === target) {
                    c.classList.remove('hidden');
                } else {
                    c.classList.add('hidden');
                }
            });
        });
    });

    // Voice Log Analyzer
    const analyzeVoiceBtn = document.getElementById('btn-analyze-voice');
    const voiceStatus = document.getElementById('voice-status');

    if (analyzeVoiceBtn) {
        analyzeVoiceBtn.addEventListener('click', async () => {
            voiceStatus.innerHTML = '<span class="text-amber-400 font-mono animate-pulse">⏳ Analyzing audio spectral frequency...</span>';
            analyzeVoiceBtn.disabled = true;

            try {
                const response = await fetch('/sandika/voice-log', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' }
                });
                const data = await response.json();
                if (response.ok) {
                    voiceStatus.innerHTML = `<span class="text-emerald-400 font-mono">✅ ${data.message}</span>`;
                    updateRankUI(data.rank);
                } else {
                    voiceStatus.innerHTML = `<span class="text-rose-400 font-mono">❌ Sign in required to gain CP.</span>`;
                }
            } catch (err) {
                voiceStatus.innerHTML = `<span class="text-rose-400 font-mono">❌ System error.</span>`;
            } finally {
                analyzeVoiceBtn.disabled = false;
            }
        });
    }

    // ROT13 Live Encoder/Decoder Tool
    const rotInput = document.getElementById('rot13-input');
    const rotOutput = document.getElementById('rot13-output');
    const rotBtn = document.getElementById('btn-rot13-convert');

    if (rotBtn && rotInput && rotOutput) {
        rotBtn.addEventListener('click', () => {
            const input = rotInput.value;
            rotOutput.value = input.replace(/[a-zA-Z]/g, function(c) {
                return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
            });
        });
    }

    // Story Form Submission
    const storyForm = document.getElementById('form-post-story');
    if (storyForm) {
        storyForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(storyForm);

            try {
                const res = await fetch('/sandika/story', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken || '' },
                    body: formData
                });
                const data = await res.json();
                if (res.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error publishing story.');
                }
            } catch (e) {
                alert('Submission error.');
            }
        });
    }

    // Dictionary Form Submission
    const dictForm = document.getElementById('form-add-dict');
    if (dictForm) {
        dictForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(dictForm);

            try {
                const res = await fetch('/sandika/dictionary', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken || '' },
                    body: formData
                });
                const data = await res.json();
                if (res.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error adding dictionary entry.');
                }
            } catch (e) {
                alert('Submission error.');
            }
        });
    }

    // Git Insight Submission
    const gitForm = document.getElementById('form-post-git');
    if (gitForm) {
        gitForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(gitForm);

            try {
                const res = await fetch('/sandika/git', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken || '' },
                    body: formData
                });
                const data = await res.json();
                if (res.ok) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error logging git insight.');
                }
            } catch (e) {
                alert('Submission error.');
            }
        });
    }

    function updateRankUI(rank) {
        if (!rank) return;
        const xpElem = document.getElementById('user-xp-val');
        const levelElem = document.getElementById('user-level-val');
        const titleElem = document.getElementById('user-title-val');
        if (xpElem) xpElem.textContent = rank.xp;
        if (levelElem) levelElem.textContent = rank.level;
        if (titleElem) titleElem.textContent = rank.rank_title;
    }
});
