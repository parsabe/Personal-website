// resources/js/theme.js

export function initThemeSwitcher() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const iconLight = document.getElementById('theme-icon-light');
    const iconDark = document.getElementById('theme-icon-dark');
    const html = document.documentElement;
    const body = document.body;

    if (!themeToggleBtn) return; // Guard clause in case the button isn't on the page

    function getInitialTheme() {
        if (localStorage.getItem('theme')) {
            return localStorage.getItem('theme');
        }
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    function applyTheme(theme, isInitialLoad = false) {
        if (theme === 'dark') {
            html.classList.add('dark');
            body.classList.remove('light');
            body.classList.add('dark');
            iconLight.classList.remove('hidden');
            iconDark.classList.add('hidden');
        } else {
            html.classList.remove('dark');
            body.classList.remove('dark');
            body.classList.add('light');
            iconDark.classList.remove('hidden');
            iconLight.classList.add('hidden');
        }
        
        // Add smooth transitions ONLY after the initial load to prevent weird flashing on refresh
        // Add smooth transitions ONLY after the initial load
        if (isInitialLoad) {
            setTimeout(() => {
                // Animate html, body, main container, and all images smoothly
                const elementsToAnimate = [
                    html, 
                    body, 
                    document.getElementById('main-container'),
                    ...document.querySelectorAll('img')
                ];
                
                elementsToAnimate.forEach(el => {
                    if(el) el.classList.add('transition-all', 'duration-700', 'ease-in-out');
                });
            }, 50);
        } else {
            // Save to localStorage only if the user manually clicked the button
            localStorage.setItem('theme', theme);
        }
    }

    let currentTheme = getInitialTheme();
    // Pass 'true' to indicate this is the first load
    applyTheme(currentTheme, true);

    themeToggleBtn.addEventListener('click', () => {
        currentTheme = currentTheme === 'light' ? 'dark' : 'light';
        applyTheme(currentTheme);
    });

    // Listen for OS theme changes in real-time
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        // Only auto-switch if the user hasn't explicitly saved a preference via the button
        if (!localStorage.getItem('theme')) {
            currentTheme = event.matches ? 'dark' : 'light';
            applyTheme(currentTheme);
        }
    });
}