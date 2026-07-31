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

    // Arkham Spirit Cipher Submission Forms
    const arkhamForms = document.querySelectorAll('.form-arkham-spirit');
    arkhamForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const spiritId = form.getAttribute('data-spirit-id');
            const answerInput = form.querySelector('input[name="answer"]');
            const answer = answerInput ? answerInput.value : '';
            const resultBox = document.querySelector(`.arkham-result-${spiritId}`);

            if (!answer.trim()) return;

            if (resultBox) resultBox.innerHTML = '<span class="text-amber-400 font-mono animate-pulse">⏳ Deciphering Arkham Spirit...</span>';

            try {
                const res = await fetch('/sandika/arkham', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({ spirit_id: spiritId, answer: answer })
                });

                const data = await res.json();

                if (res.ok || data.status === 'already_solved') {
                    if (resultBox) resultBox.innerHTML = `<span class="text-emerald-400 font-mono">✅ ${data.message}</span>`;
                    if (data.rank) updateRankUI(data.rank);

                    // Hide decipher form
                    form.classList.add('hidden');

                    // Update status badge
                    const badge = document.getElementById(`arkham-status-badge-${spiritId}`);
                    if (badge) {
                        badge.innerHTML = `<span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-[10px] font-mono font-bold flex items-center gap-1">✅ Deciphered & Unlocked</span>`;
                    }

                    // Reveal unlocked audio card
                    const audioCard = document.getElementById(`arkham-audio-card-${spiritId}`);
                    if (audioCard) {
                        audioCard.classList.remove('hidden');
                    }

                    // Automatically start playing audio log on first solve
                    if (data.status === 'success') {
                        setTimeout(() => window.toggleArkhamAudio(spiritId), 300);
                    }
                } else {
                    if (resultBox) resultBox.innerHTML = `<span class="text-rose-400 font-mono">❌ ${data.message || 'Incorrect cipher.'}</span>`;
                }
            } catch (e) {
                if (resultBox) resultBox.innerHTML = '<span class="text-rose-400 font-mono">❌ Network error.</span>';
            }
        });
    });

    // Global Interactive Audio Player Controls
    let activeSpiritAudioId = null;

    window.toggleArkhamAudio = function(spiritId) {
        const audio = document.getElementById(`arkham-audio-player-${spiritId}`);
        const btn = document.getElementById(`arkham-play-btn-${spiritId}`);

        if (!audio) return;

        // Pause any other currently playing spirit audio
        if (activeSpiritAudioId && activeSpiritAudioId !== spiritId) {
            const prevAudio = document.getElementById(`arkham-audio-player-${activeSpiritAudioId}`);
            const prevBtn = document.getElementById(`arkham-play-btn-${activeSpiritAudioId}`);
            if (prevAudio) prevAudio.pause();
            if (prevBtn) prevBtn.innerText = '▶';
        }

        if (audio.paused) {
            audio.play().then(() => {
                if (btn) btn.innerText = '⏸';
                activeSpiritAudioId = spiritId;
            }).catch(err => {
                console.log('Audio playback permission blocked:', err);
            });
        } else {
            audio.pause();
            if (btn) btn.innerText = '▶';
        }
    };

    window.seekArkhamAudio = function(spiritId, percent) {
        const audio = document.getElementById(`arkham-audio-player-${spiritId}`);
        if (audio && audio.duration) {
            audio.currentTime = (percent / 100) * audio.duration;
        }
    };

    window.replayArkhamAudio = function(spiritId) {
        const audio = document.getElementById(`arkham-audio-player-${spiritId}`);
        if (audio) {
            audio.currentTime = 0;
            window.toggleArkhamAudio(spiritId);
        }
    };

    window.updateArkhamAudioProgress = function(spiritId) {
        const audio = document.getElementById(`arkham-audio-player-${spiritId}`);
        const seek = document.getElementById(`arkham-seek-${spiritId}`);
        const timeCurr = document.getElementById(`arkham-time-curr-${spiritId}`);
        const timeDur = document.getElementById(`arkham-time-dur-${spiritId}`);

        if (!audio || isNaN(audio.duration)) return;

        const current = audio.currentTime;
        const duration = audio.duration;

        if (seek) {
            seek.value = (current / duration) * 100;
        }

        if (timeCurr) {
            const mins = Math.floor(current / 60);
            const secs = Math.floor(current % 60);
            timeCurr.innerText = `${mins}:${secs < 10 ? '0' : ''}${secs}`;
        }

        if (timeDur) {
            const mins = Math.floor(duration / 60);
            const secs = Math.floor(duration % 60);
            timeDur.innerText = `${mins}:${secs < 10 ? '0' : ''}${secs}`;
        }
    };

    window.onArkhamAudioEnded = function(spiritId) {
        const btn = document.getElementById(`arkham-play-btn-${spiritId}`);
        if (btn) btn.innerText = '▶';
        const seek = document.getElementById(`arkham-seek-${spiritId}`);
        if (seek) seek.value = 0;
    };

    window.switchSandikaTab = function(targetId) {
        const tabButtons = document.querySelectorAll('.sandika-tab-btn');
        const tabContents = document.querySelectorAll('.sandika-tab-content');
        tabButtons.forEach(b => {
            if (b.getAttribute('data-target') === targetId) {
                b.click();
            }
        });
    };

    function updateRankUI(rank) {
        if (!rank) return;
        const xpElem = document.getElementById('user-xp-val');
        const levelElem = document.getElementById('user-level-val');
        const titleElem = document.getElementById('user-title-val');
        if (xpElem) xpElem.textContent = rank.xp + ' CP';
        if (levelElem) levelElem.textContent = rank.level;
        if (titleElem) titleElem.textContent = rank.rank_title;
    }
});
