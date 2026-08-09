/**
 * Nigma Full Riddler Concept ESM Module
 */
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const solveBtn = document.getElementById('btn-solve-riddle');
    const answerInput = document.getElementById('nigma-answer-input');
    const nigmaResult = document.getElementById('nigma-result');

    const activeTitle = document.getElementById('active-riddle-title');
    const activeText = document.getElementById('active-riddle-text');
    const activeCipher = document.getElementById('active-riddle-cipher');
    const activeIdInput = document.getElementById('active-riddle-id');

    // Riddle item selection
    document.querySelectorAll('.riddle-select-item').forEach(item => {
        item.addEventListener('click', () => {
            const id = item.getAttribute('data-id');
            const title = item.getAttribute('data-title');
            const text = item.getAttribute('data-text');
            const cipher = item.getAttribute('data-cipher');

            if (activeTitle) activeTitle.textContent = title;
            if (activeText) activeText.textContent = `"${text}"`;
            if (activeCipher) activeCipher.textContent = cipher;
            if (activeIdInput) activeIdInput.value = id;

            if (nigmaResult) nigmaResult.innerHTML = '';
            if (answerInput) {
                answerInput.value = '';
                answerInput.focus();
            }
        });
    });

    if (solveBtn && answerInput) {
        solveBtn.addEventListener('click', async () => {
            const answer = answerInput.value.trim();
            const puzzleId = activeIdInput ? activeIdInput.value : 1;

            if (!answer) {
                nigmaResult.innerHTML = '<span class="text-amber-400 font-mono">⚠️ Please type your riddle solution first.</span>';
                return;
            }

            nigmaResult.innerHTML = '<span class="text-emerald-400 font-mono animate-pulse">⚙️ Decrypting Riddler cipher key...</span>';

            try {
                const response = await fetch('/nigma/solve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({ puzzle_id: puzzleId, answer: answer })
                });

                const data = await response.json();

                if (response.ok) {
                    nigmaResult.innerHTML = `<span class="text-emerald-400 font-mono font-bold">❓ RIDDLE SOLVED! ${data.message}</span>`;
                    answerInput.value = '';
                    setTimeout(() => location.reload(), 1500);
                } else {
                    nigmaResult.innerHTML = `<span class="text-rose-400 font-mono">❌ ${data.message || 'Incorrect cipher key.'}</span>`;
                }
            } catch (err) {
                nigmaResult.innerHTML = '<span class="text-rose-400 font-mono">❌ Network transmission error.</span>';
            }
        });
    }
});
