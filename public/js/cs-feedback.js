/**
 * CS Feedback Form ESM Module
 */
document.addEventListener('DOMContentLoaded', () => {
    // Runaway Prank Button Script ("I do not want to vote")
    const noBtn = document.getElementById('btn-no-vote');
    const card = document.getElementById('feedback-card');

    if (noBtn && card) {
        const voteContainer = document.getElementById('vote-container');
        noBtn.style.position = 'absolute';
        
        resetNoButtonPosition();

        function resetNoButtonPosition() {
            noBtn.style.left = '';
            noBtn.style.right = '0px';
            noBtn.style.top = '10px';
        }

        function moveNoButton() {
            const containerHeight = card.clientHeight;
            const btnWidth = noBtn.clientWidth;
            const btnHeight = noBtn.clientHeight;
            
            const cardRect = card.getBoundingClientRect();
            const containerRect = voteContainer.getBoundingClientRect();
            
            const minY = cardRect.top - containerRect.top + 20;
            const maxY = cardRect.bottom - containerRect.top - btnHeight - 20;
            
            const minX = cardRect.left - containerRect.left + 20;
            const maxX = cardRect.right - containerRect.left - btnWidth - 20;

            let newX = Math.random() * (maxX - minX) + minX;
            let newY = Math.random() * (maxY - minY) + minY;

            noBtn.style.right = '';
            noBtn.style.left = `${newX}px`;
            noBtn.style.top = `${newY}px`;
        }

        noBtn.addEventListener('mouseenter', moveNoButton);
        noBtn.addEventListener('mouseover', moveNoButton);
        noBtn.addEventListener('touchstart', (e) => {
            e.preventDefault();
            moveNoButton();
        });

        noBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    }
});
