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

    function applyTheme(theme) {
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
        localStorage.setItem('theme', theme);
    }

    let currentTheme = getInitialTheme();
    applyTheme(currentTheme);

    themeToggleBtn.addEventListener('click', () => {
        currentTheme = currentTheme === 'light' ? 'dark' : 'light';
        applyTheme(currentTheme);
    });

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        if (!localStorage.getItem('theme')) {
            applyTheme(event.matches ? 'dark' : 'light');
        }
    });
}