/**
 * Admin Dashboard Real-Time Search ESM Module
 */
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('dashboard-search');
    
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.dashboard-card');
            
            cards.forEach(card => {
                const content = card.getAttribute('data-searchable') || '';
                if (content.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});
