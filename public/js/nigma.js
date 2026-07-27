/**
 * Nigma Riddler Cypher Portal ESM Module
 */
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const solveBtn = document.getElementById('btn-solve-riddle');
    const answerInput = document.getElementById('nigma-answer-input');
    const nigmaResult = document.getElementById('nigma-result');

    if (solveBtn && answerInput) {
        solveBtn.addEventListener('click', async () => {
            const answer = answerInput.value.trim();
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
                    body: JSON.stringify({ answer })
                });

                const data = await response.json();

                if (response.ok) {
                    nigmaResult.innerHTML = `<span class="text-emerald-400 font-mono font-bold">❓ RIDDLE SOLVED! ${data.message}</span>`;
                    answerInput.value = '';
                } else {
                    nigmaResult.innerHTML = '<span class="text-rose-400 font-mono">❌ Incorrect cipher key.</span>';
                }
            } catch (err) {
                nigmaResult.innerHTML = '<span class="text-rose-400 font-mono">❌ Network transmission error.</span>';
            }
        });
    }
});
