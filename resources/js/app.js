import './bootstrap';
import { initThemeSwitcher } from './theme.js';

// Initialize the theme switcher when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    initThemeSwitcher();
});



import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
